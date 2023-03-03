<?php

require_once "./connect.php";
require_once "./Follows.php";
require_once "./includes/User.php";
require_once "./includes/Tweet.php";

session_start();

if (!isset($_POST['myTweet'])) {
  header("location:/index.php");
}

$user = $_SESSION['user'];
$text = $_POST['myTweet'];
$text = sanitizeSQL($_POST['myTweet']);

$tweet = new Tweet();
$tweet->userId = $user->userId;
$tweet->tweetText = $text;
Tweet::PostTweet($tweet);

header("location:/index.php");
