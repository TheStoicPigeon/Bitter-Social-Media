$('#search_modal').on('show.bs.modal', function(event) {

  let status = $('.alert-info');
  status.hide();

  $('#search-header').on('click', function() {
    $('#search-body').show();
  })


  $('#search_submit').on('click', function(event) {
    event.preventDefault();

    if ($('#search_query').val().trim() == "") {
      $('#search_query').val('');
      $('#search_results').html('');
      return;
    }

    search($('#search_query').val(), function(result) {

      $('#search_results').hide();

      if (result == 'empty') {
        status.html('Sorry. No matches found');
        status.show();

      } else {

        status.hide();
        $('#search_results').html(result);
        $('#search_results').show();
      }
    });

  })//end search_submit click
});//end show.bs.modal


$('#search_modal').on('hidden.bs.modal', function(event) {
  $('#search_query').val('');
  $('#search_results').html('');
  $('#search-body').show();
});






function search(query, callback) {

  let req = new XMLHttpRequest();
  req.open('POST', 'search_proc.php', true);
  req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  req.send('query=' + query);

  req.onreadystatechange = function() {
    if (this.readyState = 4 && this.status == 200) {

      let result = this.responseText;


      if (result == '') {
        callback('empty');
      } else {
        callback(result);
      }
    }
  }
} //end search

