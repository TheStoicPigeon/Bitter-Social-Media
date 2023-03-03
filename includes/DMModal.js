$('#dm_modal').on('show.bs.modal', function(event) {

  $("#write_msg").on('click', function() {
    console.log('input clicked');
    $('#write_msg').attr('rows', 5);
    $('#write_msg').css('border', '2px solid #00fcb6')
    $('#write_msg').attr('placeholder', '');
  });
  $("#write_msg").on('blur', function() {
    $('#write_msg').attr('rows', 1);
    $('#write_msg').css('border', '1px solid #1ba0ff')
    $('#write_msg').attr('placeholder', 'write something here');
  });

  GetConversations(function(result) {
    $('.dm_inbox').html(result);
    $('#dm_inbox_prompt').hide();
  });

  $('.write_container').hide();

}); //end messages Modal


//WHEN A CONVERSATION OR SEARCHED USER SELECTED
function ConversationSelected(user) {

  // //refresh conversation to keep looking or new messages
  // setInterval(function() {
  //   GetMessages(user)
  // }, 2000);


  //indicate which conversation is active
  $('.conversation').each(function() {
    $(this).css('border-right', '');
  });

  //get conversation messages
  GetMessages(user);
  $('.write_container').show();


  //send message
  $('#send_msg').on('click', function() {
    SendMessage(user);
    $('#write_msg').val('');
  })

}//end ConversationSelected





function SendMessage(recipient) {

  if ($('#write_msg').val() != '') {

    let text = $('#write_msg').val();

    let msg = new XMLHttpRequest();
    msg.open('POST', 'DirectMessage_proc.php', true);
    msg.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    msg.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        GetMessages(recipient);
      }
    }
    msg.send(`new_msg=${text}&recipient=${recipient}`);
  }
}// end SendMessage


function SearchFollowing(userId) {

  $('dum_results').hide();

  let query = $('#dm_search').val().replace(/[^A-Za-z0-9\s!?]/g, "");
  if (query.length === 0) {
    $('.dm_search_inbox').html('');
    $('.dm_inbox').show();
    return
  } else {
    $('.dm_inbox').hide();
  }

  let following = new XMLHttpRequest();
  following.open("POST", "UserSearch_AJAX.php", true);
  following.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  following.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      $('.dm_search_inbox').html(this.responseText);

    }
  }
  following.send(`query=${query}`);

}



function GetConversations(callback) {


  let conversations = new XMLHttpRequest();
  conversations.open('POST', 'DirectMessage_proc.php', true);
  conversations.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  conversations.send(`conversations`);

  conversations.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
}


function GetMessages(user) {

  $(`#conversation_${user}`).css('border-right', '3px solid #00fcb6');

  let messages = new XMLHttpRequest();
  messages.open('POST', 'DirectMessage_proc.php', true);
  messages.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  messages.send(`messages=${user}`);

  messages.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      $('#dm_results').html(this.responseText);
      $('#dm_msg_prompt').hide();
    }
  }
}
