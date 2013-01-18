$(document).ready(function() {
    $('#add_hash').click(function(e) {
        e.preventDefault();

        var form = $(e.target).parents('form');
        var key     = form.find('input[name="key"]').val().trim();
        var hashkey = form.find('input[name="hashkey"]').val().trim();
        var str     = form.find('textarea[name="value"]').val().trim();

        if (key != '' && str != '' && hashkey != '') {
            $.ajax({
                url: baseurl+'/hashes/add',
                dataType: 'json',
                type: 'POST',
                data: 'key='+key+'&value='+str+'&hashkey='+hashkey,
                success: function(data) {
                    form.find('input').val('');
                    form.find('textarea').val('');

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
