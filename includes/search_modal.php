<?php


echo <<<_SEARCH_MODAL

  <div class="modal fade" id="search_modal">
    <div class="modal-dialog" id="search_modal">
      <div class="modal-content">
        <div class="modal-header" id="search-header">
          <h2 class="modal-title">Search</h2>
        </div>
        <div class="modal-body">
          <div id='search-body'>
            <div class='alert alert-info' role='alert'></div>
            <form method="post" action="" id='search_form' enctype="multipart/form-data">
              <input style="width:100%" type="text" id='search_query' name="query" required="required"><br><br>
              <div class="modal-footer">
                <input type="submit" id='search_submit' value='Search' class="btn btn-primary" />
                <button type="button" id='search_close' class="btn btn-primary" data-dismiss="modal">Discard</button>
              </div>
            </form>
          </div>
          <div id='search_results'></div>
        </div>
      </div>
    </div>
  </div>

_SEARCH_MODAL;
