<?php

echo <<<_DMM_MODAL

  <div class="modal fade" id="dm_modal">
    <div class="modal-dialog modal-lg" id="dm_modal_dialog">
      <div class="modal-content">
        <div class="modal-header" id="dm_header">
          <h2 class="modal-title">Messages</h2>
        </div>
        

        <div class="modal-body container-fluid dm_body">

          <div class="row dm_container" id="dm_container_left">
            <input id='dm_search' name="query" onkeyup="SearchFollowing({$user->userId})" autocomplete="off" placeholder="Search for people">

            <div class='dm_inbox'> </div> 
            <div class='dm_search_inbox'></div>

            <div id="dm_inbox_prompt">
              <p>Welcome message</p>
              <input type="submit" value="Start a conversation">
            </div>
          </div>

          
          <div class="row dm_container" id="dm_container_right">
            <div id='dm_result_header'></div>
            <div id='dm_results'></div>

            <div id="dm_msg_prompt">
              <p>You don't have any message selected</p>
            </div>
            <div class='write_container'>
              <div class='write_container_inner'>
              <textarea id='write_msg' rows="1" autocomplete='off' maxlength='250' placeholder='write something here'></textarea>
              <img id='send_msg' src='../Images/send.svg' alt='send message' />
</div>
            </div>
          </div>

        </div>

                
      </div>
    </div>

  </div>

_DMM_MODAL;
