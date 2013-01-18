var error = function() {
    $('#generalmodal').find('h3').text('Error');
    $('#generalmodal').find('p').text('There was a problem saving the value');
    $('#generalmodal').find('button').removeClass('btn-success');
    $('#generalmodal').find('button').addClass('btn-danger');

    $('#generalmodal').modal('show');
}

var saved = function() {
    $('#generalmodal').find('h3').text('Saved');
    $('#generalmodal').find('p').text('New value inserted');
    $('#generalmodal').find('button').removeClass('btn-danger');
    $('#generalmodal').find('button').addClass('btn-success');

    $('#generalmodal').modal('show');
}

var invalid = function() {
    $('#generalmodal').find('h3').text('Invalid');
    $('#generalmodal').find('p').text('Please enter a valid input');
    $('#generalmodal').find('button').removeClass('btn-success');
    $('#generalmodal').find('button').addClass('btn-danger');

    $('#generalmodal').modal('show');
}
