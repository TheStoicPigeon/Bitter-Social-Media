<?php

echo <<<_PROFILE_MODAL

<div class="modal fade" id="profile_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Select a new profile pic</h2>
      </div>
      <div class="modal-body">
        <div class='alert alert-warning' role='alert'></div>
        <form method="post" action="edit_photo_proc.php" id='profile_pic_form' enctype="multipart/form-data">
          <input type="file" id='profile_pic' accept="image/*" name="photo" required="required"><br><br>
          <div class="modal-footer">
            <input type="submit" id='profile_pic_submit' value='Confirm' class="btn btn-primary" />
            <button type="button" class="btn btn-primary" data-dismiss="modal">Discard</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

_PROFILE_MODAL;
