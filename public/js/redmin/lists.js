$(document).ready(function() {
    $('#add_list').click(function(e) {
        e.preventDefault();

        var form  = $(e.target).parents('form');
        var key   = form.find('input[name="key"]').val().trim();
        var str   = form.find('textarea[name="value"]').val().trim();
        var type  = form.find('select[name="position"]').val();
        var pivot = form.find('input[name="pivot"]');

        if (key != '' && str != '' && (type == 'append' || type == 'prepend' || type == 'before' || type == 'after')) {
            if ((type == 'before' || type == 'after') && pivot.val().trim() == '') {
                invalid();
            } else{
                console.log(pivot, typeof(pivot),typeof(pivot) != 'undefined');
                if (pivot.length > 0)
                    pivot = pivot.val().trim();
                else
                    pivot = '';

                $.ajax({
                    url: baseurl+'/lists/add',
                    dataType: 'json',
                    type: 'POST',
                    data: 'key='+key+'&value='+str+'&type='+type+'&pivot='+pivot,
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
            }
        } else {
            invalid();
        }
    });

    $('#list_position').change(function(e) {
        var val = $(e.target).val();

        if (val == 'after' || val == 'before') {
            if ($('#list_type').find('input').length <= 0) {
                $('<input type="text" placeholder="Pivot Value" name="pivot" />').appendTo($('#list_type'));
            }
        } else {
            $('#list_type').empty();
        }
    });
});
