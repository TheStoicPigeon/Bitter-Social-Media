<?php

echo <<<_REPLY_MODAL

      <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="replyModalLabel">Reply to</h5>
            </div>
            <div class="modal-body">
              <form method="post" id="replyForm" enctype="multipart/form-data">
                <textarea class="form-control col-xs-12" rows="7" style="min-width:100%;background-color:#f4f8fb;" id='replyText' name="reply" value="" maxlength="280" ></textarea><br><br>
                <div class='alert alert-info' role='alert' id='reply_status'></div>
                <div class="modal-footer">
                  <input type="submit" id="reply_submit" value='Respond' class="btn btn-primary" />
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Forget</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

_REPLY_MODAL;
