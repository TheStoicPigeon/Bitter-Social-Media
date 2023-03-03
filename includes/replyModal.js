$('#replyModal').on('show.bs.modal', function(event) {

  $('#reply_status').hide();
  var trigger = $(event.relatedTarget);
  let data = trigger[0].dataset.tweetinfo;

  [name, ...rest] = data.split('|');

  var modal = $(this);
  modal.find('.modal-title').text(`Replying to ${name}`);


  var reply = $('#replyText').val();

  $('#replyText').on('input', function() {
    if ($('#replyText').val().length == 280) {
      $('#reply_status').html("Max tweet length reached!");
      $('#reply_status').show();
    } else {
      $('#reply_status').hide();
    }
  });



  $('#reply_submit').on('click', function(e) {


    e.preventDefault();
    let reply = $('#replyText').val();

    submitReply(data, reply, function(response) {
      if (response == 1) {
        $('#replyModal').modal('hide');
      }

    });

  })//end form submit

})//end show modal

$('#replyModal').on('hidden.bs.modal', function() {
  location.reload();
})

function submitReply(data, reply, callback) {

  let xhr = new XMLHttpRequest();

  xhr.open('POST', 'reply_proc.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send('tweet=' + data + '&reply=' + reply);

  xhr.onreadystatechange = function() {
    if (this.readyState = 4 && this.status == 200) {

      if (this.responseText == 1) {
        callback(1);
      }


    }

  }
}
