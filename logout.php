<?php

//log the user out and redirect them back to the login page.
session_start();
if (isset($_SESSION["user"])) {
  if (isset($_GET["logMeOut"])) {
    session_unset();
    session_destroy();
    header("location:login.php");
  } else
    header("location:index.php");
} else {
  header("location:login.php");
}
