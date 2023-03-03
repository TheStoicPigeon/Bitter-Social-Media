<?php

require_once("./connect.php");
require_once("Follows.php");
require_once("./includes/User.php");

if (isset($_POST['checkEmail'])) {

  $email = $_POST['checkEmail'];
  $result = USER::GetUserByEmail($email);
  echo "$result";
} else {
  header("location:index.php");
}


if (isset($_POST['updateUser'])) {
  $data = $_POST['updateUser'];
  $user = new User();
  list($userId, $first, $last, $email, $phone, $addr, $prov, $postal, $url, $desc, $loc) = explode('|', $data);
  User::UpdateUser($userId, $first, $last, $email, $phone, $addr, $prov, $postal, $url, $desc, $loc);
}
