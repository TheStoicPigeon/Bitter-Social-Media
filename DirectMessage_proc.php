<?php

require_once("connect.php");
require_once("Follows.php");
require_once("./includes/Tweet.php");
require_once("./includes/User.php");

session_start();

$user = $_SESSION['user'];

if (isset($_POST['query'])) {

  $query = sanitizeSQL($_POST['query']);

  $names = array_values($user->follows);
  $regex = "/" . $query . "/i";
  $result = preg_grep($regex, $names);

  $response = "";

  if ($result) {
    foreach ($result as $id => $name) {
      $response .= "<option value=$name>";
    }
  }
  echo $response;
}


//GET CONVERSATIONS
if (isset($_POST['conversations'])) {

  print(User::GetConversations($user->userId));
  $_POST = array();
}


//GET CONVERSATION MESSAGES
if (isset($_POST['messages'])) {
  $recipient = $_POST['messages'];
  print(User::GetMessages($user->userId, $recipient));
  $_POST = array();
}


//SEND A DIRECT MESSAGE
if (isset($_POST['new_msg'])) {
  $msg = $_POST['new_msg'];
  $recipient = $_POST['recipient'];
  User::SendMessage($user->userId, $recipient, $msg);
  $_POST = array();
}
