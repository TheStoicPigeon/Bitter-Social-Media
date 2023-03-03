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

$user = $_SESSION['user']; //whole user object

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Bitter - Social Media for Trolls, Narcissists, Bullies and Presidents">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <?php
  include_once("./Includes/head.php");
  include_once "./Includes/header.php";
  include_once "./includes/search_modal.php";
  include_once "./includes/profile_modal.php";
  include_once "./includes/reply_modal.php";
  include_once "./includes/DMModal.php";
  ?>

  <title>Home</title>

</head>

<body>
  <span id="currentUser" hidden><?= $user->userId ?></span>
  <div class="make_tweet">
    <div class="make_tweet_inner">
      <div class="img-rounded">
        <form method="post" id="tweet_form" action="tweet_proc.php">
          <div class="form-group">
            <textarea class="form-control" name="myTweet" id="my_Tweet" rows="6" maxlength='280' placeholder='Enter a tweet'></textarea>
            <input type="submit" name="button" id="button" value="Send" class="btn btn-primary btn-lg btn-block login-button" />
          </div>
        </form>
      </div>
    </div>
    <div class="tweet_prompt">
      <h3 id='tweet_label'>Go ahead... let it out</h3>
    </div>
  </div>

  <BR><BR>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="mainprofile img-rounded">
          <div class="bold">
            <img class="profile-icon" src="<?= $user->profImage ?>" />
            &nbsp;<?= $user->getFullName() ?><BR>
          </div>
          <table>
            <tr>
              <td><img src="./Images/tweetIcon.svg" style="height:30px;width:30px;padding-right:5px;" /></td>
              <td>Tweets</td>
              <td> <?= $user->getCountTweets() ?>
              </td>
            </tr>
            <tr>
              <td><img src="./Images/followingZombie.svg" style="height:30px;width:30px;padding-right:5px" /></td>
              <td>Following</td>
              <td><?= $user->getCountFollows() ?>
              </td>
            </tr>
            <tr>
              <td><img src="./Images/followers.svg" style="height:30px;width:30px;padding-right:5px" /></td>
              <td>Followers</td>
              <td><?= $user->getCountFollowers() ?>
              </td>
            </tr>

          </table><br>
        </div><BR><BR>

        <div class=" trending img-rounded">
          <div class="bold">Trending</div>
          <h3>Top Liked</h3>
          <?php print(Tweet::getTrendingByLikes()); ?>
          <hr>
          <h3>Top Retweeted</h3>
          <?php print(Tweet::getTrendingByRetweets()); ?>
          <hr>
          <h3>Top Replied</h3>
          <?php print(Tweet::getTrendingByReplies()); ?>
        </div>
      </div>

      <div class="col-md-6 tweets-container">
        <!--make tweet was here -->
        <div class="img-rounded">
          <!--display list of tweets here-->
          <?php
          Tweet::GetTweets($user->userId);
          ?>

        </div>
      </div>
      <div class="col-md-3">
        <div class="whoToTroll img-rounded">
          <div class="whoToTroll-inner">
            <div class="row whoToTroll-Title">
              <p>Who to Troll?</p><BR>
            </div>
            <hr>
            <!-- display people you may know here-->

            <div class='trolls'>
              <?php
              print(User::whoToTroll($user->userId));
              ?>
            </div>
          </div>
        </div><BR>
      </div>
    </div> <!-- end row -->
  </div>
  <!-- /.container -->


  <?php include "./includes/footer.php"; ?>


  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script src="includes/bootstrap.min.js"></script>
  <script src="includes/index.js"></script>
  <script src="includes/replyModal.js"></script>
  <script src="includes/DMModal.js"></script>
  <script src="includes/searchModal.js"></script>
  <!--<script src="includes/profilePicModal.js"></script>-->

</body>

</html>
