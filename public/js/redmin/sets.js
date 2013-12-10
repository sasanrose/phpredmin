$(document).ready(function() {
    $('#add_set').click(function(e) {
        e.preventDefault();

        var form = $(e.target).parents('form');
        var key  = form.find('input[name="key"]').val().trim();
        var str  = form.find('textarea[name="value"]').val().trim();

        if (key != '' && str != '') {
            $.ajax({
                url: baseurl+'/sets/add',
                dataType: 'json',
                type: 'POST',
                data: 'key='+key+'&value='+str,
                success: function(data) {
                    if (data) {
                        var oldkey = form.find('input[name="oldkey"]');
                        form.find('textarea').val('');
                        if (oldkey.length > 0) {
                            var tr = $('.settable tr:first');
                            $('<tr><td>'+str+'</td><td><a href="'+baseurl+'/sets/edit/'+encodeURIComponent(key)+'/'+encodeURIComponent(str)+'" target="_blank" class="action"><i class="icon-edit"></i></a></td><td><a href="#" class="action del"><i class="icon-trash" keytype="sets" keyinfo="'+key+'" id="'+str+'"></i></a></td>'+
                              '<td><input type="checkbox" name="keys[]" value="'+str+'" /></td></tr>').insertAfter(tr);
                            $('.settable tr').eq(1).find('a.del').click(function(e) {
                                deleteRow(e);
                            });
                        } else {
                            form.find('input').val('');
                        }

                        saved();
                    } else
                        error();
                }
            });
        } else {
            invalid();
        }
    });
});