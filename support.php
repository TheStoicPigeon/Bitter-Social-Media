<?php
define('CIPHER', 'AES-128-CTR');
define('VECTOR', '1046123703921412');
define('KEY', 'NACHOEVERYDAYENCRYPTIONKEY');

require_once("./connect.php");
require_once("Follows.php");
require_once("./includes/User.php");


session_start();


//handle people resetting their passwords
if (isset($_GET['RetrieveBitterAccount'])) {
  $data = $_GET['RetrieveBitterAccount'];
  list($userId, $reqTime) = explode('|', decrypt($data));
  //only access page if the person clicked the link in under 5minutes
  if ((time() - $reqTime) < 300) {
    $_SESSION['retrieveUser'] = $userId;
    header("location:bitter_reset.php");
  } else {
    header("location:login.php?msg=You waited too long.  Request another link");
  }
}



//handle message sent from contactUs page
//a complete hack job
if (isset($_POST['contactUs'])) {

  list($from, $name, $msg) = explode('|', $_POST['contactUs']);

  $url = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
  $user = 'BITTERSOCIALMEDIA';
  $pass = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
  $email = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

  $json_string = array(
    'to' => array(
      $email
    ),
    'category' => 'test_category'
  );

  $params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'x-smtpapi' => json_encode($json_string),
    'to'        => $email,
    'subject'   => 'Message from Bitter user',
    'html'      => "from:" . $from . "\r\nMESSAGE: " . $msg,
    'body'      => 'text body',
    'from'      => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
  );

  $request =  $url . 'api/mail.send.json';
  $headr = array();
  $headr[] = 'Authorization: Bearer ' . $pass;

  // Generate curl request
  $session = curl_init($request);
  if ($session) {
    // Tell curl to use HTTP POST
    curl_setopt($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    // Tell PHP not to use SSLv3 (instead opting for TLS)
    //curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    // add authorization header
    curl_setopt($session, CURLOPT_HTTPHEADER, $headr);
    // obtain response
    $response = curl_exec($session);
    curl_close($session);
    echo "success";
  } else {
    echo "invalid";
  }
}




//handle checks to verify an email
//return a userID of the email to be used later to reset password
if (isset($_POST['checkEmail'])) {
  $email = $_POST['checkEmail'];
  $result = USER::GetUserByEmail($email);
  echo "$result";
}




//handle requests to send an email
if (isset($_POST['sendEmail'])) {
  list($email, $userId) = explode('|', $_POST['sendEmail']);

  $url = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
  $user = 'BITTERSOCIALMEDIA';
  $pass = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
  $email = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

  $json_string = array(
    'to' => array(
      $email
    ),
    'category' => 'test_category'
  );

  $params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'x-smtpapi' => json_encode($json_string),
    'to'        => $email,
    'subject'   => 'Bitter Account retrieval',
    'html'      => "Please follow the link to reset your Bitter password\r\nhttp://localhost/support.php?RetrieveBitterAccount=" . encrypt($userId),
    'body'      => 'text body',
    'from'      => 'bitter_social_media',
  );

  $request =  $url . 'api/mail.send.json';
  $headr = array();
  $headr[] = 'Authorization: Bearer ' . $pass;

  // Generate curl request
  $session = curl_init($request);
  if ($session) {
    // Tell curl to use HTTP POST
    curl_setopt($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    // Tell PHP not to use SSLv3 (instead opting for TLS)
    //curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    // add authorization header
    curl_setopt($session, CURLOPT_HTTPHEADER, $headr);
    // obtain response
    $response = curl_exec($session);
    curl_close($session);
    echo "success";
  } else {
    echo "invalid";
  }
}




//Handle password reset call from bitter-reset.php
if (isset($_POST['password'])) {

  $pass = $_POST['password'];
  $userId = (int)$_POST['userId'];
  if (USER::UpdatePassword($pass, $userId)) {
    echo "success";
  } else {
    echo "error";
  }
}




//Handle changing personal info from account.php
if (isset($_POST['updateAccount'])) {
  list($userId, $first, $last, $email, $phone, $addr, $prov, $post, $url, $desc, $loc) = explode("|", $_POST['updateAccount']);
  if (USER::UpdateUser($userId, $first, $last, $email, $phone, $addr, $prov, $post, $url, $desc, $loc)) {
    echo "success";
  } else {
    echo "error";
  }
  unset($_POST['updateAccount']);
}




//when account has been updated force them to sign back in to refresh data
if (isset($_POST['accountUpdated'])) {
  unset($_SESSION['user']);
  session_destroy();
  echo "destroyed";
}




//DELETE AN ACCOUNT
if (isset($_POST['deleteAccount'])) {
  $userId = $_POST['deleteAccount'];
  if (User::DeleteUser($userId)) {
    unset($_SESSION['user']);
    echo "deleted";
  } else {
    echo "error";
  }
}


//CHANGE A USERNAME
if (isset($_POST['newUsername'])) {
  list($username, $userId) = explode('|', $_POST['newUsername']);
  if (USER::ChangeUsername($username, $userId)) {
    echo "success";
    unset($_SESSION['user']);
  } else {
    echo "error";
  }
}



//my hack job for trying to make resetting passwords secure
//I combine the data plus the request time and encrypt it.  
//Now I can control how long the link I sent them will work 
function encrypt($data): string
{
  return openssl_encrypt($data . "|" . time(), CIPHER, KEY, 0, VECTOR);
}

function decrypt($data): string
{
  return openssl_decrypt($data, CIPHER, KEY, 0, VECTOR);
}
