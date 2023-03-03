<?php

require_once('connect.php');
require_once("Follows.php");
require_once("./includes/User.php");

session_start();

if (isset($_SESSION["user"])) {
  header("location:index.php");
}

if (isset($_POST["firstname"])) {

  $firstName = $_POST["firstname"];
  $lastName = $_POST["lastname"];
  $email = $_POST["email"];
  $screenName = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $phoneNumber = $_POST["phone"];
  $address = $_POST["address"];
  $province = $_POST["province"];
  $postalCode = $_POST["postalCode"];
  $url = $_POST["url"];
  $description = $_POST["desc"];
  $location =  $_POST["location"];


  $user = new User();

  $user->firstName = $firstName;
  $user->lastName = $lastName;
  $user->email = $email;
  $user->userName = $screenName;
  $user->password = $password;
  $user->address = $address;
  $user->province = $province;
  $user->postalCode = $postalCode;
  $user->contactNo = $phoneNumber;
  $user->url = $url;
  $user->location = $location;
  $user->description = $description;


  if (User::AddUser($user)) {
    $msg = "$screenName ACCOUNT CREATED SUCCESSFULLY";
    echo "success";
  } else {
    $msg = "Something went wrong adding User to database";
    echo "error";
  }
} else {
  // header("location:index.php");
}
