<?php

require_once("connect.php");
require_once("Follows.php");
require_once("./includes/Tweet.php");
require_once("./includes/User.php");

session_start();

$user = $_SESSION['user'];

if ($_POST['query']) {

  $query = sanitizeSQL($_POST['query']);

  Search(addslashes($query), $user);
} else {
  header("location:index.php");
}



function Search(string $query, User $user)
{

  $responseUsers = User::SearchUsers($query, $user);
  $responseTweets = Tweet::SearchTweets($query, $user);

  echo $responseUsers;
  echo $responseTweets;
}
