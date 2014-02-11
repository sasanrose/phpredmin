var modalShow = function(type, message, title) {
    if (typeof type === 'undefined') {
        type = 'saved';
    }
    
    var modalDefault = {
        saved : {
            title : 'Saved',
            message : 'New value inserted',
            btn_class : 'btn-success'
        },
        error : {
            title : 'Error',
            message : 'There was a problem saving the value',
            btn_class : 'btn-danger'
        },
        invalid : {
            title : 'Invalid',
            message : 'Please enter a valid input',
            btn_class : 'btn-danger'
        }
    };
    
    if (typeof title === 'undefined') {
        title = modalDefault[type]['title'];
    }
    if (typeof message === 'undefined') {
        message = modalDefault[type]['message'];
    }
    $('#generalmodal').find('h3').text(title);
    $('#generalmodal').find('p').text(message);
    $('#generalmodal').find('button').attr('class', 'btn ' + modalDefault[type]['btn_class']);
    $('#generalmodal').modal('show');
}
