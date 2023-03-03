import * as Utils from "./utils.js";

var first, last, scr, email, pword, phone, addr, prov, postal, url, desc, loc;

$(document).ready(function() {


  let restarted = false;

  $('.show-result').hide();
  $('#prev-ctrl').hide();
  let slide = 0;

  $('#username').off('keyup');
  $('#username').on('input', Utils.validateUsername);
  $('#postalCode').on('input', Utils.validatePostal);
  $('#province').on('change', Utils.validateProvince);
  $('#login_submit').on('click', login);

  $('#signup-carousel').on('slide.bs.carousel', function(e) {
    let index = $(this).find('.active').index();
    let dir = e.direction;

    if (restarted) {
      setTimeout(function() {
        $('#signup-form input').val(''); //clear inputs
        $('#signup-form input').attr('placeholder', ''); //clear inputs
        $('#next-ctrl').html('Next');
        $('.show-result').hide();
        $('#prev-ctrl').hide();
        slide = 0;
      }, 350);
    }

    if (index == 1 && dir == 'right') {
      $('#prev-ctrl').hide();
    } else {
      $('#prev-ctrl').show();
    }

    if (index == 3) {
      if (dir == 'left') {
        $('#next-ctrl').html('Confirm');
      } else {
        $('#next-ctrl').html('Next');
      }
    }
  });


  $('.login-container').on('click', function() {
    $('#login-form').show();
    $('.login-container').css("transform", "translateY(0)");
    $('#intro_one').css('transform', 'translateX(100%)');
    $('#intro_two').css('transform', 'translateX(-100%)');
    restarted = true;
    $('#signup-carousel').carousel(0); //reset carousel
  });

  $('.signup-container').on('click', function() {
    $('.login-container').css("transform", "translateY(80%)");
    $('#intro_one').css('transform', 'translateX(0)');
    $('#intro_two').css('transform', 'translateX(0)');
    setTimeout(function() {
      $('#login-form').hide();
    }, 300);
  });

  //----------------PREV ON CLICK----------------------
  //Don't allow them to go back from page 1
  $('#prev-ctrl').on('click', function() {
    if (slide == 5) {
      $('#next-ctrl').html('Next');
      $('#signup-title').html('SIGNUP');
    }
    $('#signup-carousel').carousel('prev')
    slide--;
  }); //end prev onClick


  //----------------NEXT ON CLICK----------------------
  $('#next-ctrl').on('click', function() {

    restarted = false;
    if (slide == 0) {
      $('#signup-carousel').carousel('next');
      slide++;

    }
    else if (slide == 1) {

      first = Utils.validateFirstName($('#firstname'));
      last = Utils.validateLastName($('#lastname'));
      if (first && last) {
        Utils.validateEmail($('#email'), function(result) {
          if (result == -1) {
            first = $('#firstname').val();
            last = $('#lastname').val();
            email = $('#email').val();
            $('#signup-carousel').carousel('next');
            slide++;
          } else {
            $('#email').val('');
            $('#email').css('background-color', '#7fffd4');
            $('#email').attr('placeholder', 'Email already in use');
          }
        });
      }
    }


    else if (slide == 2) {

      scr = Utils.validateUsername();
      pword = Utils.validatePassword($('#password'));
      let conf = Utils.validateMatchingPasswords($('#password'), $('#confirm'));

      if (pword && conf) {
        Utils.checkScreenName($('#username'), function(result) {
          if (result) {

            scr = $('#username').val();
            pword = $('#password').val();

            $('#signup-carousel').carousel('next');
            slide++;
          }
        })
      }
    } //------------SLIDE 3-----------------

    else if (slide == 3) {

      phone = Utils.validatePhoneNumber($('#phone'));
      addr = Utils.validateAddress($('#address'));
      postal = Utils.quickPostal($('#postalCode'));
      prov = Utils.quickProvince($('#province'));

      if (phone && addr && prov) {

        Utils.validatePostal($('#postalCode'), function(result) {
          // if (result == prov) {
          phone = $('#phone').val();
          addr = $('#address').val();
          postal = $('#postalCode').val();
          prov = $('#province').val();
          $('#signup-carousel').carousel('next');
          slide++;
          // } else {
          //   $('#province').css("background-color", "#7fffd4");
          //   $('#province').children('option:eq(0)').html("Province doesn't match postal code given");
          //   $('#province').prop('selectedIndex', 0);
          // }
        });

      }
    } //------------------------SLIDE 4-------------------------------

    else if (slide == 4) {
      url = Utils.validateUrl($('#url'));
      desc = Utils.validateDescription($('#desc'));
      loc = Utils.validateLocation($('#location'));
      if (url && desc && loc) {
        url = $('#url').val();
        desc = $('#desc').val();
        loc = $('#location').val();

        $('#signup-title').html("Looks like you're all set");
        $('#first').html(first);
        $('#last').html(last);
        $('#scr').html(scr);
        $('#mail').html(email);
        $('#addr').html(addr);
        $('#pro').html(prov);
        $('#pc').html(postal);
        $('#num').html(phone);
        $('#u').html(url);
        $('#d').html(desc);
        $('#loc').html(loc);

        $('#signup-carousel').carousel('next');
        slide++;
      }
    }//---------------------Slide 5---------------------------

    else if (slide == 5) {

      sendData(function(result) {

        if (result == 'success') {
          $('#result-span').html(`<h3>Your account is all set up!</h3><h5>Click on the "LOGIN" below to sign in</h5>`);
          $('.show-result').show();
        } else {
          $('#result-span').html(`It looks like we experienced an error on our end.<br>Please refresh page and try again`);
          $('.show-result').show();

        }
      }); //end sendData
    }//--------------------END SLIDE 5

  }); //end next onClick
});//end document.onReady

function sendData(callback) {
  const formData = new FormData();
  formData.append('firstname', first);
  formData.append('lastname', last);
  formData.append('username', scr);
  formData.append('email', email);
  formData.append('password', pword);
  formData.append('address', addr);
  formData.append('province', prov);
  formData.append('postalCode', postal);
  formData.append('phone', phone);
  formData.append('url', url);
  formData.append('desc', desc);
  formData.append('location', loc);

  let xml = new XMLHttpRequest();
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

  let username = $('#login_username');
  let password = $('#login_password');

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
