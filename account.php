<?php

require_once("./connect.php");
require_once("Follows.php");
require_once("./includes/User.php");
require_once("./includes/Tweet.php");

session_start();

if (!isset($_SESSION["user"])) {
  header("location:login.php");
} elseif (isset($_GET['msg'])) {
  $message = $_GET['msg'];
  echo "<script>alert('$message')</script>";
}

$user = $_SESSION['user'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Bitter - Social Media for Trolls, Narcissists, Bullies and Presidents">
  <meta name="author" content="Jeremy Boss, Email: boss.jm@outlook.com">

  <title>Account</title>

  <!-- Bootstrap core CSS -->
  <link href="includes/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="includes/starter-template.css" rel="stylesheet">
  <link href="includes/account.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <!--ADDED THIS -->

  <?php
  include_once("./Includes/head.php");
  include_once "./Includes/header.php";
  include_once "./includes/search_modal.php";
  include_once "./includes/profile_modal.php";
  include_once "./includes/reply_modal.php";
  ?>

</head>

<body>

  <BR><BR>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2">
        <div class="mainprofile img-rounded">
          <div class="bold">
            <img class="profile-icon" src="<?= $user->profImage ?>">
            <?= $user->getFullName() ?><BR>
          </div>
          <table>
            <tr>
              <td><img src="./Images/tweetIcon.svg" style="height:30px;width:30px;padding-right:5px;" /></td>
              <td>Tweets</td>
              <td> <?= $user->getCountTweets() ?></td>
            </tr>
            <tr>
              <td><img src="./Images/followingZombie.svg" style="height:30px;width:30px;padding-right:5px" /></td>
              <td>Following</td>
              <td><?= $user->getCountFollows() ?></td>
            </tr>
            <tr>
              <td><img src="./Images/followers.svg" style="height:30px;width:30px;padding-right:5px" /></td>
              <td>Followers</td>
              <td><?= $user->getCountFollowers() ?></td>
            </tr>
          </table>
          <div class="member-since">
            <div class="member-from"><img class="icon" src="images/home.svg"><?= $user->province ?></div>
            <div><b>Member Since:</b></br><?= date("F jS, Y", strtotime($user->dateAdded)) ?></div>
          </div>
        </div><BR><BR>

        <!--I removed Followers You know from the current User's userpage-->
        <?php
        if ($user->userId != $user->userId) {
          echo "<div class='trending img-rounded'>";
          User::FollowersYouKnow($user, $user);
          echo "</div>";
        }
        ?>
      </div>

      <div class="col-md-3 px-0 settings">
        <div class="settings_header">
          <h2>Settings</h2>
        </div>

        <!---------------- MAIN SETTINGS OPTIONS (LEFT) -------------------->

        <div class="main_folder">
          <div class="settings_row" data-hidden="true" data-option="account" data-header="Change information about your account.  Choose a different screen name.  Change your password">
            <p>Your account</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>
        </div>
        <div class="main_folder">
          <div class="settings_row" data-hidden="true" data-option="notifications" data-header="Make adjustments to your notifications settings">
            <p>Notifications</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>
        </div>
        <div class="main_folder">
          <div class="settings_row" data-hidden="true" data-option="display" data-header="Change the way bitter looks and feels">
            <p>Display</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>
        </div>

      </div>

      <!-------------------- SUB OPTIONS (RIGHT CONTAINER) ----------------------->

      <div class="col-md-6 px-0 sub_settings">

        <div class="sub_folder_header">
          <h3>This is the header</h3>
        </div>

        <div class="sub_folder" id="account">

          <div class="option" data-option='screenname'>
            <p>Change your screen name</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>

          <div class="option" data-option='personalInfo'>
            <p>Change your personal information</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>

          <div class="option" data-option='deleteAccount'>
            <p>Delete your account</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>

          <div class="option" data-option='profilePic'>
            <p>Change your profile pic</p>
            <img src="./Images/chevron_right.svg" alt="open">
          </div>
        </div>


        <div class="sub_folder" id="notifications">
          <p>More to come</p>
        </div>

        <div class="sub_folder" id="display">
          <p>More to come</p>
        </div>


        <!-------------- CHANGE SCREEN NAME  ------------------>

        <div class="option_result" id="change_username_form">
          <h3>Warning</h3>
          <p>You will need be required to sign in after this operation</p>
          <form>
            <label for="username">Enter a new screen name</label><br>
            <input type="text" class="form-control" id="username" /><br>
            <input type="submit" id="change_username_submit" value="CHANGE" data-NSN="<?= $user->userId ?>" />
          </form>
        </div>


        <!-------------- CHANGE PERSONAL INFORMATION SECTION  ------------------>

        <?php $altUser = User::GetTempUser($user->userId); ?>

        <div class="option_result" id="pers_info">
          <h3>Warning</h3>
          <p>You will need be required to sign in after this operation</p>
          <form id="pers_info_form">
            <div class='settings_form_row'>
              <label for="firstname">First Name</label><br>
              <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $altUser->firstName ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="lastname">Last Name</label><br>
              <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $altUser->lastName ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="email">Email</label><br>
              <input type="text" class="form-control" name="email" id="email" value="<?= $altUser->email ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="phone">Phone Number</label><br>
              <input type="text" class="form-control" name="phone" id="phone" value="<?= $altUser->contactNo ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="address">Address</label><br>
              <input type="text" class="form-control" required name="address" id="address" value="<?= $altUser->address ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="province">Province</label><br>
              <select name="province" id="province" class="form-control" data-province="<?= $altUser->province ?>" required>
                <option value="NIL"></option>
                <option value="BC">British Columbia</option>
                <option value="AB">Alberta</option>
                <option value="Sk">Saskatchewan</option>
                <option value="MB">Manitoba</option>
                <option value="ON">Ontario</option>
                <option value="QC">Quebec</option>
                <option value="NB">New Brunswick</option>
                <option value="PE">Prince Edward Island</option>
                <option value="NS">Nova Scotia</option>
                <option value="NL">Newfoundland and Labrador</option>
                <option value="NT">Northwest Territories</option>
                <option value="NU">Nunavut</option>
                <option value="YT">Yukon</option>
              </select>
            </div>
            <div class='settings_form_row'>
              <label for="postalCode">Postal Code</label><br>
              <input type="text" class="form-control" required name="postalCode" id="postalCode" value="<?= $altUser->postalCode ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="url">URL</label><br>
              <input type="text" class="form-control" name="url" id="url" value="<?= $altUser->url ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="desc">Description</label><br>
              <input type="text" class="form-control" name="desc" id="desc" value="<?= $altUser->description ?>" />
            </div>
            <div class='settings_form_row'>
              <label for="location">Location</label><br>
              <input type="text" class="form-control" name="location" id="location" value="<?= $altUser->location ?>" />
            </div>
            <input type="submit" id="pers_info_submit" data-id="<?= $altUser->userId ?>" value="update" />
          </form>
        </div>
        <!------end profile option--->


        <div class='result_show'></div>

        <!-------------- DELETE ACCOUNT SECTION  ------------------>

        <div class="option_result" id="deleteAccount_sub">
          <h3>There ain't no coming back from this chief</h3></br>
          <p>No seriously. We don't keep backups.</p>
          <input type="submit" id="delete_submit" value="DELETE ACCOUNT" data-deleteId="<?= $user->userId ?>" />
        </div>



      </div>
      <!----------------END RIGHT CONTAINER---------------------->

    </div>
  </div> <!-- end row -->
  </div><!-- /.container -->



  <?php include "./includes/footer.php"; ?>

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script type="module" src="includes/account.js"></script>
  <script src="includes/bootstrap.min.js"></script>
  <script src="includes/replyModal.js"></script>
  <script src="includes/searchModal.js"></script>
  <script src="includes/profilePicModal.js"></script>

</body>

</html
