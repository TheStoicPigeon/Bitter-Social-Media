<?php

require_once("./connect.php");
require_once("Follows.php");
require_once("./includes/User.php");
require_once("./includes/Tweet.php");

session_start();

$user = $_SESSION['user'];
$tempUser = User::GetTempUser($_GET['user']);

if (!isset($_GET['user'])) {
  header("location:index.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Bitter - Social Media for Trolls, Narcissists, Bullies and Presidents">
  <meta name="author" content="Jeremy Boss, Email: boss.jm@outlook.com">
  <link rel="icon" href="favicon.ico">

  <title>Home</title>

  <!-- Bootstrap core CSS -->
  <link href="includes/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="includes/starter-template.css" rel="stylesheet">
  <link href="includes/userpage.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <!--ADDED THIS -->
  <script src="includes/profilePicModal.js"></script>

  <?php include "./Includes/header.php";
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
      <div class="col-md-3">
        <div class="mainprofile img-rounded">
          <div class="bold">
            <img class="profile-icon" src="<?= $tempUser->profImage ?>">
            <?= $tempUser->getFullName() ?><BR>
          </div>
          <table>
            <tr>
              <td><img src="./Images/tweetIcon.svg" style="height:30px;width:30px;padding-right:5px;" /></td>
              <td>Tweets</td>
              <td> <?= $tempUser->getCountTweets() ?></td>
            </tr>
            <tr>
              <td><img src="./Images/followingZombie.svg" style="height:30px;width:30px;padding-right:5px" /></td>
              <td>Following</td>
              <td><?= $tempUser->getCountFollows() ?></td>
            </tr>
            <tr>
              <td><img src="./Images/followers.svg" style="height:30px;width:30px;padding-right:5px" /></td>
              <td>Followers</td>
              <td><?= $tempUser->getCountFollowers() ?></td>
            </tr>
          </table>
          <div class="member-since">
            <div class="member-from"><img class="icon" src="images/home.svg"><?= $tempUser->province ?></div>
            <div><b>Member Since:</b></br><?= date("F jS, Y", strtotime($tempUser->dateAdded)) ?></div>
          </div>
        </div><BR><BR>

        <!--I removed Followers You know from the current User's userpage-->
        <?php
        if ($user->userId != $tempUser->userId) {
          echo "<div class='trending img-rounded'>";
          User::FollowersYouKnow($tempUser, $user);
          echo "</div>";
        }
        ?>

      </div>
      <div class="col-md-8">
        <div class="img-rounded">
          <?php
          Tweet::GetTweets($tempUser->userId, true);
          ?>
        </div>
        <div class="img-rounded">

        </div>
      </div>

    </div>
  </div> <!-- end row -->
  </div><!-- /.container -->


  <?php include "./includes/footer.php"; ?>

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script src="includes/bootstrap.min.js"></script>
  <script src="includes/index.js"></script>
  <script src="includes/replyModal.js"></script>
  <script src="includes/searchModal.js"></script>

</body>

</html>
