$(document).ready(function() {

  $('#email').val('');
  $('#send_email').on('click', verifyEmail);


}); //end on Ready

function verifyEmail(e) {

  console.log('here');
  e.preventDefault();

  if (validateEmail($('#email'))) {

    let email = $('#email').val();

    let xml = new XMLHttpRequest();
    xml.open("POST", "../support.php");
    xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        //check if response 
        let userId = this.responseText;
        if (userId == -1) { //email not in DB
          $('#email').css('background-color', "#7fffd4");
          $('[for=email]').text('Email does not exist');
        } else {
          sendEmail(email, userId);
        }
      }
    }
    xml.send(`checkEmail=${email}`);
  }
}

function sendEmail(email, userId) {
  console.log('inside sendEmail');
  console.log(email);
  let xml = new XMLHttpRequest();
  xml.open('POST', '../support.php');
  xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let result = this.responseText;
      console.log(result);
      if (result == "invalid") {
        $('#email').css('background-color', "#7fffd4");
        $('[for=email]').text("Oops.  We're having problems on our end");
      } else {
        $('[for=email]').text('Check your inbox . . . and your junk folder');
        $('#send_email').hide();
        $('#email').hide();
      }
    }
  }
  xml.send(`sendEmail=${email}|${userId}`);
}




function validateForm(event) {

  console.log('inside validateform');
  console.log($('#submitReset').data('userId'));
  event.preventDefault();

  let userId = $('#submitReset').data('userId');

  let pass = $('#p1');
  let conf = $('#p2');


  if (validatePassword(pass) && validateMatchingPasswords(pass, conf)) {

    let newPass = pass.val();
    let xml = new XMLHttpRequest();
    xml.open('POST', 'support.php', true);
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        window.location.href = `localhost/index.php?msg=${this.responseText}`;
      }
    } //end onreadystatechange
    xml.send(`password=${newPass}&userId=${userId}`);
  }
} //end validateForm


function validateEmail(id) {
  if (id.val().trim() == '') {
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Email required');
    return false;
  } else if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(id.val().trim())) {
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", `Invalid email.  Please try again`);
    return false;
  } else {
    id.css("background-color", '');
    email = id.val();
    return true;
  }
}

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



