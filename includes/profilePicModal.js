$('#profile_modal').on('show.bs.modal', function(event) {

  let status = $('.alert-warning');

  status.hide();
  let MAX_FILE_SIZE = 2 * 1024 * 1024; //5MB

  $('#profile_pic_submit').on("click", (e) => {

    e.preventDefault()
    let image = $('#profile_pic')[0].files;

    if (image[0]['size'] > MAX_FILE_SIZE) {
      status.html('Oops. Looks like the file is too large. Max size 2MB');
      status.show();
    }
    else {
      $('#profile_pic_form').submit();
    }
  });
})

