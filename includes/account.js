import * as Utils from "./utils.js";

$(document).ready(() => {


  $('.settings_row').on('click', settingClicked);
  $('.option').on('click', showOptions);


  $('.sub_folder').hide();
  $('.option_result').hide();
  $('sub_folder_header').hide();
  $('selection_result').hide();
}); //end ready



//highlight current selected item
function settingClicked(event) {
  $('.result_show').hide(); //hide any previous result

  $('.settings_row').each(function() {
    $(this).css('border-right', '');
  })
  $(this).css('border-right', '3px solid #00fcb6');

  let selectedRow = $(this).data('option');

  $('.sub_folder_header').html($(this).data('header'));
  showFolder(selectedRow);
  $('.option_result').hide();
  $('.sub_folder_header').show();

}

function showFolder(selected) {

  $('.sub_folder').hide();

  if (selected === 'account') {
    $('#account').show();
  }
  else if (selected === 'notifications') {
    $('#notifications').show();
  }
  else if (selected === 'display') {
    $('#display').show();
  }
}

function showOptions(event) {
  console.log(event);
  $('.sub_folder').hide();
  let option = $(this).data('option');

  //----------------CHANGE USERNAME------------------
  if (option === 'screenname') {
    $('.sub_folder_header').hide();
    $('#username_result').show();
    $('#change_username_form').show();
    $('#username').on('input', checkUsername)
  }

  //----------------CHANGE PROFILE PICTURE------------------
  else if (option === 'profilePic') {
    $('#profile_modal').modal('show');
  }

  //----------------DELETE ACCOUNT------------------
  else if (option === 'deleteAccount') {
    $('.sub_folder_header').hide();
    $('#deleteAccount_sub').show();
    $('#delete_submit').on('click', deleteAccount);
  }

  //----------------UPDATE ACCOUNT------------------
  else if (option === 'personalInfo') {
    $('.sub_folder_header').hide();
    let province = $('#province')[0].dataset['province'];
    $('#province').val(province).change();
    $('#pers_info').show();
    $('#pers_info_submit').on('click', validatePersInfo);
  }
}


function checkUsername() {
  let screenname = $('#username');
  Utils.checkScreenName(screenname, function(result) {
    if (result) {
      $('#change_username_submit').on('click', function(e) {
        e.preventDefault();
        let newUsername = screenname.val();
        changeUsername(newUsername, function(result) {
          if (result == 'success') {
            window.location.href = "login.php";
          }
        })
      });
    }
  });
}

function changeUsername(newScreenName, callback) {

  let userId = $('#change_username_submit')[0].dataset['nsn'];

  let xml = new XMLHttpRequest();
  xml.open("POST", "../support.php");
  xml.setRequestHeader("content-type", "application/x-www-form-urlencoded");
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      let result = this.responseText;
      console.log(result);
      callback(result);
    }
  }
  xml.send(`newUsername=${newScreenName}|${userId}`);
}


function deleteAccount() {
  $('#deleteAccount_sub').hide();
  // $('.result_show').html("Ahh just kiddin. I haven't gotten that far yet");
  // $('.result_show').show();

  let userId = $('#delete_submit')[0].dataset['deleteid'];
  let xml = new XMLHttpRequest();
  xml.open("POST", "../support.php", true);
  xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let result = this.responseText;
      if (result == "deleted") {
        window.location.href = "login.php";
      } else {
        $('.result_show').html("Woopsie.  Looks like there's an error somewhere. Try again later");
      }
    }
  }
  xml.send(`deleteAccount=${userId}`);
}


//AJAX call to account_proc 
function getSettings(option) {

  let xml = new XMLHttpRequest();
  xml.open("post", "account_proc.php", true);
  xml.setRequestHeader("Content-type", "application/x-www-urlencoded");

}


function validatePersInfo(e) {
  e.preventDefault();

  let first = Utils.validateFirstName($('#firstname'))
  let last = Utils.validateLastName($('#lastname'));
  let phone = Utils.validatePhoneNumber($('#phone'));
  let addr = Utils.validateAddress($('#address'));
  let province = Utils.quickProvince($('#province'));


  if (first && last && phone && addr && province) {

    let postal = Utils.validatePostal($('#postalCode'), function(result) {
      if (result == province) {

        let oldEmail = $('#email')[0].defaultValue;//keep the original email
        if ($('#email').val() == oldEmail) { //email hasn't changed
          console.log('everything valid');
          updateAccount();
        }
        else {
          Utils.validateEmail($('#email'), function(resp) {
            if (resp == -1) {
              console.log('valid with email changed');
              updateAccount();
            }
            else {
              console.log("email isn't valid");
              $('#email').val('Email already taken');
              $('#email').css('background-color', '#7fffd4');
            }
          });
        }
      } else {
        $('#province').css("background-color", "#7fffd4");
        $('#province').children('option:eq(0)').html("Province doesn't match postal code given");
        $('#province').prop('selectedIndex', 0);
      }
    });
  }
}

function updateAccount(e) {

  let userId = $('#pers_info_submit')[0].dataset['id'];
  let first = $('#firstname').val();
  let last = $('#lastname').val();
  let email = $('#email').val();
  let phone = $('#phone').val();
  let addr = $('#address').val();
  let prov = $('#province').val();
  let postal = $('#postalCode').val();
  let url = $('#url').val();
  let desc = $('#desc').val();
  let loc = $('#location').val();


  let xml = new XMLHttpRequest();
  xml.open("POST", "../support.php", true);
  xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let result = this.responseText;
      if (result == "success") {
        accountUpdated();
      } else {
        $('.result_show').html("Woopsie.  Looks like there's an error somewhere. Try again later");
      }
    }
  }
  xml.send(`updateAccount=${userId}|${first}|${last}|${email}|${phone}|${addr}|${prov}|${postal}|${url}|${desc}|${loc}`);
}

function accountUpdated() {
  let xml = new XMLHttpRequest();
  xml.open("POST", "../support.php", true);
  xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      let result = this.responseText;
      if (result == "destroyed") {
        window.location.href = "login.php";
      }
    }
  }
  xml.send(`accountUpdated=true`);

}


