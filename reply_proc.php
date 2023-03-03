<?php

require_once('./connect.php');
require_once('Follows.php');
require_once('./includes/User.php');
require_once('./includes/Tweet.php');

session_start();

if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];

  if (isset($_POST['tweet'])) {

    $data = $_POST['tweet'];

    list($name, $userId, $tweetId, $originalTweetId, $replyToTweetId, $dateAdded, $text) = explode('|', $data);
    $reply = $_POST['reply'];


    $tweet = new Tweet();

    $tweet->userId = $user->userId;
    $tweet->originalTweetId = $originalTweetId;
    $tweet->tweetText = sanitizeSQL($reply);
    $tweet->replyToTweetId = $tweetId;

    print(Tweet::PostTweet($tweet));
  } else {
    header("location:index.php");
  }
} else {
  header("location:/login.php");
}
