<?php

require_once("connect.php");
require_once("Follows.php");
require_once("./includes/User.php");

if (isset($_POST['usr'])) {

  $checkUser = $_POST['usr'];
  $result = User::CheckUsername(strtolower($checkUser));

  if ($result) {
    echo $checkUser; //return each letter back to form
  } else {
    echo '*'; //return true back to form
  }
} else {
  header("location:index.php");
}
