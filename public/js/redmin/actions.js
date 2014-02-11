var deleteRow = function(e) {
    e.preventDefault();

    var tr      = $(e.target).parents('tr');
    var type    = $(e.target).attr('keytype');
    var keyinfo = $(e.target).attr('keyinfo');
    var id      = $(e.target).attr('id');

    if (typeof(keyinfo) == 'undefined') {
        var url = baseurl+'/'+type+'/delete/'+currentServerDb+'/'+encodeURIComponent(id);
    } else {
        var url = baseurl+'/'+type+'/delete/'+currentServerDb+'/'+encodeURIComponent(keyinfo)+'/'+encodeURIComponent(id);
    }

    $('.modal-footer .deletekey').unbind();
    $('.modal-footer .deletekey').click(function() {
        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                $('#del_confirmation').modal('hide');

                if (data == 1) {
                    tr.remove();
                }
            }
        });
    });

    $('#del_confirmation').modal('show');
}

$(document).ready(function() {
    $('.del').click(function(e) {
        deleteRow(e);
    });

    $('#checkall').click(function(e) {
        $("input[name=keys\\[\\]]").attr('checked', $(e.target).is(':checked'));
    });

    $('.moveall').click(function(e) {
        e.preventDefault();
        var checkboxes = $("input[name=keys\\[\\]]:checked");

        if (checkboxes.length > 0) {
            $('.modal-footer .movekey').unbind();
            $('.modal-footer .movekey').click(function() {
                var destination = $('.modal-body input').val().trim();
                var type       = $(e.target).attr('keytype');
                var keyinfo    = $(e.target).attr('keyinfo');

                if (destination != '') {
                    var values = [];
                    checkboxes.each(function() {
                        values.push($(this).val());
                    });

                    if (typeof(keyinfo) == 'undefined') {
                        var postdata = {'values[]': values, 'destination': destination};
                    } else {
                        var postdata = {'values[]': values, 'destination': destination, 'keyinfo': keyinfo};
                    }

                    $.post(
                        baseurl+'/'+type+'/moveall/'+currentServerDb,
                        postdata,
                        function(data) {
                            $('#move_confirmation').modal('hide');

                            checkboxes.each(function() {
                                if (data[$(this).val()]) {
                                    $(this).parents('tr').remove();
                                }
                            });
                        }
                    );
                };
            });
            var title = $(e.target).attr('modaltitle');
            var tip   = $(e.target).attr('modaltip');

            $('#move_confirmation').find('h3').text(title);
            $('#move_confirmation').find('input').attr('placeholder', tip);
            $('#move_confirmation').modal('show');
        } else {
            modalShow('invalid')
        }
    });


    $('.delall').click(function(e) {
        e.preventDefault();
        var checkboxes = $("input[name=keys\\[\\]]:checked");
        var type       = $(e.target).attr('keytype');
        var keyinfo    = $(e.target).attr('keyinfo');

        if (checkboxes.length > 0) {
            $('.modal-footer .deletekey').unbind();
            $('.modal-footer .deletekey').click(function() {
                var values = [];
                checkboxes.each(function() {
                    values.push($(this).val());
                });

                if (typeof(keyinfo) == 'undefined') {
                    var postdata = {'values[]': values};
                } else {
                    var postdata = {'values[]': values, 'keyinfo': keyinfo};
                }

                $.post(
                    baseurl+'/'+type+'/delall/'+currentServerDb,
                    postdata,
                    function(data) {
                        $('#del_confirmation').modal('hide');

                        checkboxes.each(function() {
                            if (data[$(this).val()] == 1) {
                                $(this).parents('tr').remove();
                            }
                        });
                    }
                );
            });
            $('#del_confirmation').modal('show');
        } else {
            modalShow('invalid')
        }
    });
});
