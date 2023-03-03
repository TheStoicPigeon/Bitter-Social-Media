<?php
//this page will be used when the user clicks on the "follow" button for a particular user
//process the transaction and insert a record into the database, then redirect the user back
// to index.php

require_once("connect.php");
require_once("Follows.php");
require_once("./includes/User.php");
session_start();

if (!isset($_POST["follow"])) {
  header("location:index.php");
}

$user = $_SESSION['user'];


list($id, $screen_name) = explode("|", $_POST['follow']);

$result = $user->followUser($id, $screen_name);


$msg = $result == 1 ? "Successfully following $screen_name" : "Error. Something happened.  Try again later";
echo $msg;

header("location:index.php?message=$msg");
