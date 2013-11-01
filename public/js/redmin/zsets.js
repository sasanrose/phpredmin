$(document).ready(function() {
    $('#add_zset').click(function(e) {
        e.preventDefault();

        var form  = $(e.target).parents('form');
        var key   = form.find('input[name="key"]').val().trim();
        var score = form.find('input[name="score"]').val().trim();
        var str   = form.find('textarea[name="value"]').val().trim();

        if (key != '' && str != '' && score != '') {
            $.ajax({
                url: baseurl+'/zsets/add/' + currentServerDb,
                dataType: 'json',
                type: 'POST',
                data: 'key='+key+'&value='+str+'&score='+score,
                success: function(data) {
                    var oldkey = form.find('input[name="oldkey"]');
                    form.find('textarea').val('');

                    if (oldkey.length > 0) {
                        if (data)
                            location.reload();
                    } else {
                        form.find('input').val('');
                    }

                    if (data)
                        saved();
                    else
                        error();
                }
            });
        } else {
            invalid();
        }
    });
});
