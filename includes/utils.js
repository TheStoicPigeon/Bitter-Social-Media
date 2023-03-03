export function validateFirstName(id) {
  if (id.val().trim() == '') {
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'First name required');
    return false;
  } else if (id.val().length > 50) {
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'First name must be less than 50 characters');
    return false;
  } else {
    id.css("background-color", '');
    // first = id.val();
    return true;
  }
}


export function validateLastName(id) {
  if (id.val().trim() == '') {
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Last name required');
    return false;
  } else if (id.val().length > 50) {
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Last name must be less than 50 characters');
  } else {
    id.css("background-color", '');
    // last = id.val();
    return true;
  }
}


export function validateEmail(id, callback) {
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

    let xml = new XMLHttpRequest();
    xml.open("POST", "utils.php");
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        callback(this.responseText);
      }
    }
    xml.send(`checkEmail=${id.val()}`);
  }
}

export function validateUsername() {

  if (checkScreenName($('#username'), function(result) {
  }));
}

export function checkScreenName(id, callback) {

  let label = $('[for=username]');

  if (validateScreenName(id)) {

    let XHR = new XMLHttpRequest();

    XHR.open("POST", "validateUser.php", true);
    XHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    XHR.send("usr=" + id.val());

    XHR.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let reply = this.responseText;
        if (reply == '*') { //send back an error letting know screenName already in use
          let name = id.val();
          label.html('Screen name is already taken');
          id.css("background-color", "#7fffd4");
          callback(false);

        } else {
          label.text('Valid');
          id.css("background-color", "");
          callback(true)
        }
      }//end if readyState
    } //end onreadystatechange 
  } //end if validateScreenName 
  else {
    callback(false);
  }
} //end scanScreenName


export function validateScreenName(id) {
  let label = $('[for=newUsername]');
  if (id.val() == '') {
    id.attr("placeholder", 'Screen Name is required');
    id.css("background-color", "#7fffd4");
    label.text('Enter a new screen name');
    id.focus();
    return false;
  }
  else if (!/^[A-Za-z0-9]{1,50}$/.test(id.val())) {
    label.text('Invalid Screen Name. Only letters and numbers. MaxLength : 50 chars');
    id.css("background-color", "#7fffd4");
    id.focus();
    return false;
  }
  return true;
}

export function validatePassword(id) {
  let label = $('[for=password]');
  if (!/^(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/.test(id.val())) {
    id.focus();
    id.css("background-color", "#7fffd4");
    label.text(`Min one number and special char required.  Length 8-16 chars.`);
    return false;
  } else {
    id.css("background-color", '');
    label.text('Password');
    return true;
  }
}


export function validateMatchingPasswords(pass1, pass2) {
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
}

export function validatePhoneNumber(id) {
  if (!/^[(]{0,1}[0-9]{3}[)]{0,1}[-\s\.]{0,1}[0-9]{3}[-\s\.]{0,1}[0-9]{4}$/.test(id.val())) {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Please enter a valid phone number');
    return false;
  } else {
    id.css("background-color", '');
    id.attr("placeholder", 'Enter your phone number');
    // num = id.val();
    return true;
  }
}


export function validateAddress(id) {
  if (id.val().trim() == '') {
    id.focus();
    id.val('');
    id.attr("placeholder", 'Address required');
    id.css("background-color", "#7fffd4");
    return false;
  } else if (id.val().length > 200) {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Invalid address.  Max length = 200 characters');
    return false;
  } else {
    id.css("background-color", '');
    id.attr("placeholder", 'Enter your address');
    // addr = id.val();
    return true;
  }
}

export function quickProvince(id) {
  if (id[0].selectedIndex == 0) {
    id.focus();
    id.css("background-color", "#7fffd4");
    id.children('option:eq(0)').html('Please select a province');
    return false;
  } else {
    id.css("background-color", '');
    return id.val();
  }
}

export function quickPostal(id) {
  if (id.val().trim() == '') {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Postal code required');
    return false;
  } else if (!/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/.test(id.val())) {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Invalid Postal code. Please try again');
    return false;
  } else {
    id.css("background-color", '');
    id.attr("placeholder", 'Enter your Postal Code');
    return true;
  }
}

export function validatePostal(id, callback) {

  if ($('#postalCode').val().replace(/ /, '').length != 6) return;

  let label = $('[for=postalCode]')
  let XHR = new XMLHttpRequest();
  let post = id.val();

  XHR.open("GET", `includes/Fedex/ValidatePostalCodeService/ValidatePostalCodeWebServiceClient.php?postal=${post}`, true);
  XHR.send();

  XHR.onreadystatechange = function() {

    if (this.readyState == 4 && this.status == 200) {

      let result = this.responseText;
      if (result.length != 2) {
        id.css("background-color", "#7fffd4");
        label.text("Invalid Postal Code");
        return false;
      } else {
        id.css("background-color", "");
        label.text("Postal Code");
        callback(result);
        // return result;
      }
    }//end if readystate
  } //end onreadystatechange
};//end ValidatePostage;


export function validateProvince() {

  let label = $('[for=province]');

  if (!quickProvince()) {
    return false;
  } else {
    if (validPostal) { //if a postalCode has been validated
      if ($('#province').val() == validPostal) {
        label.text("");
        label.text("Province");
        return true;
      }
      $('#province').css("background-color", "#7fffd4");
      label.text("Conflicts with postal code");
      return false;
    } else {
      $('#province').css("background-color", "");
      label.text("");
      label.text("Province");
    }
  }
}


export function validateUrl(id) {
  if (id.val().length > 50) {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Maximum URL length = 50 chars');
    return false;
  } else {
    id.css("background-color", '');
    id.attr("placeholder", '');
    return true;
  }
}

export function validateDescription(id) {
  if (id.val().length > 160) {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Maximum Description length = 160 chars');
    return false;
  } else {
    id.css("background-color", '');
    id.attr("placeholder", '');
    return true;
  }
}


export function validateLocation(id) {
  if (id.val().length > 50) {
    id.focus();
    id.val('');
    id.css("background-color", "#7fffd4");
    id.attr("placeholder", 'Maximum Location length = 50 chars');
    return false;
  } else {
    id.css("background-color", '');
    id.attr("placeholder", '');
    return true;
  }
}


export function sendData(callback) {
  const formData = new FormData();
  formData.append('firstname', first);
  formData.append('lastname', last);
  formData.append('username', scr);
  formData.append('email', email);
  formData.append('password', pass);
  formData.append('address', addr);
  formData.append('province', prov);
  formData.append('postalCode', postal);
  formData.append('phone', num);
  formData.append('url', url);
  formData.append('desc', desc);
  formData.append('location', loc);

  xml = new XMLHttpRequest();
  xml.open("POST", "signup_proc.php", true);
  xml.send(formData);

  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      callback(this.responseText);

    }
  }
}

function login(e) {

  e.preventDefault();

  username = $('#login_username');
  password = $('#login_password');

  let checkEntries = true;
  if (username.val() == '') {
    username.attr("placeholder", 'Enter your username');
    checkEntries = false;

  }
  if (password.val() == '') {
    password.attr("placeholder", 'Enter your password');
    checkEntries = false;
  }

  if (!checkEntries) return;

  password.css("background-color", "");
  username.css("background-color", "");

  let fd = new FormData();
  fd.append('username', username.val());
  fd.append('password', password.val());

  let xml = new XMLHttpRequest();
  xml.open('POST', './login_proc.php', true);
  xml.onreadystatechange = function() {

    if (this.readyState == 4 && this.status == 200) {

      let result = this.responseText;

      if (result == "p") {
        password.val('');
        password.attr("placeholder", 'Invalid Password');
      }
      else if (result == "u") {
        username.val('');
        username.attr("placeholder", 'Username not found');
      }
      else if (result == "s") {
        window.location.href = "index.php";
      }
    }

  }
  xml.send(fd);
}

