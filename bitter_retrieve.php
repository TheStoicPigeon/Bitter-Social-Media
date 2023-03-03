<?php

session_start();
if (isset($_SESSION['userId'])) {
  header("location:index.php");
}
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Create an account with Bitter.  Post, Like and Share with your friends and family.">
  <meta name="author" content="Jeremy Boss, Email: boss.jm@outlook.com">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <link rel="icon" type="image/png" href="images/favicon.png">

  <title>Reset Your Bitter Account Password</title>

  <!-- Bootstrap core CSS -->
  <link href="includes/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="includes/starter-template.css" rel="stylesheet">
  <link href="includes/login.css" rel="stylesheet">
  <link href="includes/bitter_reset.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>

  <script src="includes/bootstrap.min.js"></script>
  <script src="includes/bitter_retrieve.js"></script>

</head>


<body>


  <div class='container-md main-container_retrieve'>

    <div class="validate">
      <form id="reset_form">
        <label for="email">Enter your Email</label><br>
        <input type="text" id="email" /></br>
        <input type="submit" id="send_email" value="Reset Password" />
      </form>
    </div>

  </div>

</body>


</html>
