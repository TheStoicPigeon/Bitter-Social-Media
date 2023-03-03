<?php
session_start();

require_once("connect.php");
require_once("Follows.php");
require_once("./includes/User.php");

unset($_SESSION['USER'], $_SESSION['PASS']);


if (isset($_POST["username"])) {


  $screenName = $_POST["username"];
  $password = $_POST["password"];

  $result = User::GetUser($screenName, $password);

  if ($result instanceof User) {
    $_SESSION["user"] = $result; //store whole user
    echo "s";
  } elseif ($result == "invalid password") {
    $_SESSION["PASS"] = "invalid";
    echo "p";
  } elseif (!$result) {
    $_SESSION["USER"] = "invalid";
    echo "u";
  }
} else {
  header("location:login.php");
}
