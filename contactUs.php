<?php

require_once("./connect.php");
require_once("Follows.php");
require_once("./includes/User.php");

session_start();

if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  $user->email = USER::GetUserEmail($user->userId);
}
if (isset($_GET['msg'])) {
  $message = $_GET['msg'];
  echo "<script>alert('$message')</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Need help with your Bitter account or interested in joining our team?  Drop us a line.">
  <meta name="author" content="Jeremy Boss, boss.jm@outlook.com">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <link rel="icon" type="image/png" href="images/favicon.png">

  <title>Contact</title>

  <!-- Bootstrap core CSS -->
  <link href="includes/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="includes/starter-template.css" rel="stylesheet">
  <link href="includes/contactUs.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="./includes/contactUs.js"></script>

</head>

<body>

  <?php
  echo <<<_NAV
      <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <a class="navbar-brand" href="index.php"><img src="Images/logo.svg" class="logo"></a>
        </div>
      </nav>
  _NAV;
  ?>


  <div class="container-fluid">

    <div class='mail'>
      <div class='mail_box'>
        <form method="POST" enctype="text/plain">
          <h3>Bitter about something?</h3>
          <h4>Tells us what you think</h4>
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" required placeholder="Enter your name" value="<?php
                                                                                                            if (isset($_SESSION['user'])) {
                                                                                                              print($user->getFullName());
                                                                                                            } else {
                                                                                                              echo "Enter your name";
                                                                                                            }
                                                                                                            ?>">
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" required placeholder="Enter your email" value="<?php
                                                                                                                if (isset($_SESSION['user'])) {
                                                                                                                  echo "$user->email";
                                                                                                                } else {
                                                                                                                  echo "Enter your email";
                                                                                                                }
                                                                                                                ?>">
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" rows="6"></textarea>
          </div>
          <button type="submit" id="contact_submit" class="btn btn-primary text-right">Submit</button>
        </form>
      </div>
    </div>



    <?php include "./includes/footer.php"; ?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="includes/bootstrap.min.js"></script>

</body>

</html>
