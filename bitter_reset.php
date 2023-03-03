<?php

session_start();
if (isset($_SESSION['retrieveUser'])) {
  $userId = $_SESSION['retrieveUser'];
  $_SESSION = array();
} else {
  header('location:index.php');
}
?>


<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Create an account with Bitter.  Post, Like and Share with your friends and family.">
  <meta name="author" content="Jeremy Boss, Email: boss.jm@outlook.com">
  <link rel="icon" href="./images/favicon.ico">

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
  <script src="includes/bitter_reset.js"></script>

</head>


<body>

  <main>

    <div class='container-md main-container'>

      <div class="reset">
        <form>
          <label for="p1">Enter a passsword</label><br>
          <input type="password" id="p1" /></br>
          <label for="p2">Confirm your password</label><br>
          <input type="password" id="p2" /></br>
          <input type="submit" id="submitReset" value="Submit" data-id="<?= $userId ?>" />

        </form>
      </div>

    </div>

  </main>
</body>


</html>
