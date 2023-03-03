<?php

require_once('connect.php');
require_once('Follows.php');
require_once('./includes/User.php');
session_start();

if (isset($_FILES["photo"])) {

  $user = $_SESSION['user'];

  $message = "";
  $file = $user->userId . "_" . $_FILES["photo"]["name"];
  $destFile = "./images/profilepics/" . $file;


  //valid file uploaded through POST -> move file to new location
  if (move_uploaded_file($_FILES["photo"]["tmp_name"], $destFile)) {


    //update DB and delete old file -> return true if successful
    if ($user->updateProfilePic($file)) {
      header("location:account.php");
      return;
    } else {
      unlink($_FILES["photo"]["tmp_name"]); //delete the file
      $message = "Error connecting to server. Please try again later";
      header("location:account.php?msg=" . $message);
      return;
    }
  }
  $message = "ERROR. Please try again";
  header("location:account.php?msg=" . $message);
} else {
  header("location:account.php");
}
