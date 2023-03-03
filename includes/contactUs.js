$(document).ready(function() {

  $('h4').css('transform', 'translateX(0)');
  $('h3').css('transform', 'translateX(0)');

  $('#contact_submit').on('click', function(e) {
    e.preventDefault();

    console.log('send clicked');

    let name = $('#name').val();
    let from = $('#email').val();
    let msg = $('#message').val();


    if (msg != '') {

      let xml = new XMLHttpRequest();
      xml.open('POST', 'support.php');
      xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          let result = this.responseText;
          if (result == "success") {
            console.log(result);
            alert("Message sent successfully");
            window.location.href = 'index.php';
          } else {
            console.log(result);
            alert("Sorry, but the email didn't go through");
          }
        }
      }
      xml.send(`contactUs=${from}|${name}|${msg}`);
    }
  })

});//end document.ready
