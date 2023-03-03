<?php

require_once("./connect.php");
require_once("Follows.php");
require_once("./includes/User.php");
require_once("./includes/Tweet.php");

session_start();

$user = $_SESSION['user'];
$response = "";
$query = $_POST['query'];

print(User::GetUsers($query, $user->userId, false));
