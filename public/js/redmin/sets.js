$(document).ready(function() {
    $('#add_set, #add_edit_set').click(function(e) {
        e.preventDefault();

        var form = $(e.target).parents('form');
        var key  = form.find('input[name="key"]').val().trim();
        var str  = form.find('textarea[name="value"]').val().trim();

        if (key != '' && str != '') {
            $.ajax({
                url: baseurl+'/sets/add/' + currentServerDb,
                dataType: 'json',
                type: 'POST',
                data: 'key='+key+'&value='+str,
                success: function(data) {
                    if (data) {
                        var oldkey = form.find('input[name="oldkey"]');
                        form.find('textarea').val('');
                        
                        if (oldkey.length > 0) {
                            location.reload();
                        } else {
                            if (e.target.id == 'add_edit_set') {
                                location.href = baseurl + '/keys/view/' + currentServerDb + '/' + encodeURIComponent(key);
                            } else {
                                form.find('input').val('');
                                saved();
                            } 
                        }  
                    }    
                    else {
                        error();
                    }    
                }
            });
        } else {
            invalid();
        }
    });
});
