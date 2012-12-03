
$(function() {
  var userId = $('#userId').val();
  var newUser = (typeof userId == 'undefined');
  
  $('#userForm').submit(function(e) {
    e.preventDefault();
    saveUser($(this).serialize(), newUser);
    return false;
  });

});



// DECLARE FUNCTIONS HERE

function saveUser(data, newUser) {

  $.post('saveUser', data, function(result) {
    Message.clearStickys();
    result = $.parseJSON(result);
    if (result.status == true) {
    if (newUser) {
      $('#content').html('Thank you for creating a new user. Please remember the password that you assigned them and let them know. This password is not stored anywhere in readable format.');
    }
      Message.flash('User saved succesfully', true);
    }
    else {
      Message.flash('User could not be saved', false, result.messages);
    }
  });
}
