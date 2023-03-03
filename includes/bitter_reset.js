$(document).ready(function() {

  $('#submitReset').on('click', validateForm)

}); //end on Ready


function validateForm(event) {

  event.preventDefault();


  let userId = $('#submitReset')[0].dataset['id'];

  let pass = $('#p1');
  let conf = $('#p2');


  if (validatePassword(pass) && validateMatchingPasswords(pass, conf)) {

    let newPass = pass.val();
    let xml = new XMLHttpRequest();
    xml.open('POST', 'support.php', true);
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if (this.responseText == "success") {
          window.location.href = `login.php?msg=Your password has been reset`;
        } else {
          console.log('error');
        }
      }
    } //end onreadystatechange
    xml.send(`password=${newPass}&userId=${userId}`);
  }
} //end validateForm


function validatePassword(id) {
  let label = $('[for=p1]');
  if (!/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/.test(id.val())) {
    id.focus();
    id.css("background-color", "#7fffd4");
    label.text(`Min one number and special char required.  Length 8-16 chars.`);
    return false;
  } else {
    id.css("background-color", '');
    label.text('Password');
    pass = id.val();
    return true;
  }
} //end validatePassword


function validateMatchingPasswords(pass1, pass2) {
  if (pass1.val().toLowerCase() != pass2.val().toLowerCase()) {
    pass2.focus();
    pass2.val('');
    pass2.css("background-color", "#7fffd4");
    pass2.attr("placeholder", "Password doesn't match.  Please try again");
    return false;
  } else {
    pass2.css("background-color", '');
    return true;
  }
} //end validateMatchingPasswords



