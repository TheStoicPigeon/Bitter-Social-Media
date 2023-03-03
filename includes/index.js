$(document).ready(function() {

  // getTrolls();

  $('.make_tweet').css('transform', 'translateY(-83%)');

  $(window).click(function() {
    $('.make_tweet').css('transform', 'translateY(-83%)');
    setTimeout(function() {
      $('#tweet_label').show();
      $('#button').css('top', '80%');
    }, 200);
  });

  $('.make_tweet').on('click', function(e) {
    e.stopPropagation();
    $('#tweet_label').hide();
    $('.make_tweet').css('transform', 'translateY(0)');
    $('#button').css('top', '90%');
  });


  //handle liking or retweeting through AJAX
  $('a').on('click', function(event) {
    if ($(this).data('retweeted')) {
      addToCount("retweeted", $(this).data('retweeted'))
    }
    else if ($(this).data('liked')) {
      addToCount("liked", $(this).data('liked'))
    }
  });







  //----------------Tweet Submit-------------------
  $("#tweet_form ").submit(function(e) {
    e.preventDefault();
    if ($('#my_Tweet').val() != '') {
      this.submit();
      $('.make_tweet').css('transform', 'translateY(-80%)');
    }
  }); //end of click event


  function addToCount(target, data) {

    let req = `${target}=${data}`;

    let xml = new XMLHttpRequest();
    xml.open('post', '../LRR_proc.php', true);
    xml.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
    xml.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        location.reload();
      }
    }
    xml.send(req);

  }















}); //end of ready event handler
