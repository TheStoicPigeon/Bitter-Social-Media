<?php

require_once("connect.php");
require_once("Follows.php");
require_once("./includes/User.php");
require_once("./includes/Tweet.php");

session_start();

$user = $_SESSION['user'];


if (isset($_POST['liked'])) {

  $tweet = $_POST['liked'];
  list($name, $userId, $tweetId, $originalTweetId, $replyToTweetId, $dateAdded, $text) = explode('|', $tweet);
  //check that you aren't trying to like your own tweet
  if ($user->userId != $userId) {

    //check that the user hasn't already liked the tweet
    $result = Tweet::CheckLiked($tweetId, $user->userId);

    if (!$result) {
      $result = Tweet::LikeTweet($tweetId, $user->userId);
    }
  }
} else if (isset($_POST['retweeted'])) {

  $tweet = $_POST['retweeted'];
  list($name, $userId, $tweetId, $originalTweetId, $replyToTweetId, $dateAdded, $text) = explode('|', $tweet);
  $retweet = new Tweet();
  $retweet->userId = $user->userId;
  $retweet->originalTweetId = $tweetId;
  $retweet->tweetText = $text;
  $retweet->name = $name;

  Tweet::PostTweet($retweet);
  header("location:/index.php");
}
header("location:index.php");
