<!DOCTYPE html>
<?php
session_start();


if (isset($_SESSION["user"])) {
  header("location:index.php");
} elseif (isset($_GET["msg"])) {
  $message = $_GET["msg"];
  echo "<script>alert('$message')</script>";
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

  <title>Signup</title>

  <!-- Bootstrap core CSS -->
  <link href="includes/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="includes/starter-template.css" rel="stylesheet">
  <link href="includes/login.css" rel="stylesheet">
  <!-- Bootstrap core JavaScript-->
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>

  <script src="includes/bootstrap.min.js"></script>

  <script type="module" src="includes/login.js"></script>
</head>

<body>


  <!--SIGNUP CAROUSEL-->

  <main>

    <div class='container-md main-container'>

      <div class='container signup-container '>
        <h3>SIGNUP</h3>
        <div id="signup-carousel" class="carousel slide" data-interval="false">
          <form id='signup-form' autocomplete="off">
            <ol class="carousel-indicators">
              <li data-target="#signup-carousel" class="active"></li>
              <li data-target="#signup-carousel"></li>
              <li data-target="#signup-carousel"></li>
              <li data-target="#signup-carousel"></li>
              <li data-target="#signup-carousel"></li>
              <li data-target="#signup-carousel"></li>
            </ol>

            <!--Intro-->
            <div class="carousel-item signup-item active">
              <div class='row row-signup'>
                <div class="intro">
                  <h1 id="intro_one">Bitter - Social Media for Trolls, Narcissists, Bullies and United States Presidents.</h1>
                  <h5 id="intro_two">Troll your friends, your boss, your teacher.</h5>
                </div>
              </div>
            </div>



            <!--Name and Email-->
            <div class="carousel-item signup-item">
              <div class='row row-signup'>
                <label for="firstname">First Name</label><br>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="" /> <br><br><br>
                <label for="lastname">Last Name</label><br>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="" /><br><br><br>
                <label for="email">Email</label><br>
                <input type="text" class="form-control" name="email" id="email" placeholder="" /><br><br><br>
              </div>
            </div>

            <!--ScreenName and Password-->
            <div class="carousel-item signup-item">
              <div class='row row-signup'>
                <label for="username">Screen Name</label><br>
                <input type="text" class="form-control" name="username" id="username" placeholder="" /><br><br><br>
                <label for="password">Password</label><br>
                <input type="password" class="form-control" required name="password" id="password" placeholder="" /><br><br><br>
                <label for="confirm">Confirm Password</label><br>
                <input type="password" class="form-control" required name="confirm" id="confirm" placeholder="" /><br><br><br>
              </div>
            </div>

            <!--Address and Phone-->
            <div class="carousel-item signup-item">
              <div class='row row-signup'>

                <label for="phone">Phone Number</label><br>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="" /> <br><br><br>

                <div class='row row-double'>
                  <div class="col">
                    <label for="address">Address</label><br>
                    <input type="text" class="form-control" required name="address" id="address" placeholder="" />
                  </div>

                  <div class='col'>
                    <label for="province">Province</label><br>
                    <!--<select name="province" id="province" class="textfield1" required>-->
                    <select name="province" id="province" class="form-control" required>
                      <option value="NIL"></option>
                      <option value="BC">British Columbia</option>
                      <option value="AB">Alberta</option>
                      <option value="Sk">Saskatchewan</option>
                      <option value="MB">Manitoba</option>
                      <option value="ON">Ontario</option>
                      <option value="QC">Quebec</option>
                      <option value="NB">New Brunswick</option>
                      <option value="PE">Prince Edward Island</option>
                      <option value="NS">Nova Scotia</option>
                      <option value="NL">Newfoundland and Labrador</option>
                      <option value="NT">Northwest Territories</option>
                      <option value="NU">Nunavut</option>
                      <option value="YT">Yukon</option>
                    </select>
                  </div>

                </div>


                <label for="postalCode"><br>Postal Code</label><br>
                <input type="text" class="form-control" required name="postalCode" id="postalCode" placeholder="" /><br><br><br>
              </div>
            </div>

            <!--Description, Url and Location-->
            <div class="carousel-item signup-item">
              <div class='row row-signup'>
                <label for="url">URL</label><br>
                <input type="text" class="form-control" name="url" id="url" placeholder="" /> <br><br><br>
                <label for="desc">Description</label><br>
                <input type="text" class="form-control" name="desc" id="desc" placeholder="" /><br><br><br>
                <label for="location">Location</label><br>
                <input type="text" class="form-control" name="location" id="location" placeholder="" /><br><br><br>
              </div>
            </div>


            <!--Confirm Entries Slide -->
            <div class="carousel-item signup-item">
              <div class='row confirm-container'>

                <div class='row row-confirm'>
                  <span class='signup-data'><b>First Name:</b>
                    <p id='first'></p>
                  </span>
                  <span class='signup-data'><b>Last Name:</b>
                    <p id='last'></p>
                  </span>
                </div>
                <div class='row row-confirm'>
                  <span class='signup-data'><b>Screen Name:</b>
                    <p id='scr'></p>
                  </span>
                  <span class='signup-data'><b>Email:</b>
                    <p id='mail'></p>
                  </span>
                </div>
                <div class='row row-confirm'>
                  <span class='signup-data'><b>Address:</b>
                    <p id='addr'></p>
                  </span>
                  <span class='signup-data'><b>Province:</b>
                    <p id='pro'></p>
                  </span>
                </div>
                <div class='row row-confirm'>
                  <span class='signup-data'><b>Postal Code:</b>
                    <p id='pc'></p>
                  </span>
                  <span class='signup-data'><b>Phone Number:</b>
                    <p id='num'></p>
                  </span>
                </div>
                <div class='row row-confirm'>
                  <span class='signup-data'><b>Url:</b>
                    <p id='u'></p>
                  </span>
                  <span class='signup-data'><b>Location:</b>
                    <p id='loc'></p>
                  </span>
                </div>
                <div class='row row-confirm'>
                  <span class='signup-data'><b>Description:</b>
                    <p id='d'></p>
                  </span>
                </div>

              </div>
            </div>
          </form>
          <div class="signup-controls">
            <button type="button" id='prev-ctrl' class="btn" data-slide="prev">Prev</button>
            <button type="button" id='next-ctrl' class="btn" data-slide="next">Next</button>
          </div>

          <!--Show Results slide-->
          <div class="show-result">
            <span id='result-span'></span>
          </div>

        </div>
      </div>

      <!--LOGIN FORM -->
      <div class='container md-4 login-container'>
        <h3>LOGIN</h3>
        <div id='image-wrapper'>
          <img id='login-img' src="./Images/logo.svg" />
        </div>
        <form id='login-form' autocomplete="off">
          <div class='row row-login'>
            <label for="login_username">Screen Name</label>
            <input class='form-control' type="text" class="form-control" name="login_username" id="login_username" placeholder="" /> <br>
          </div>
          <div class='row row-login'>
            <label for="login_password">Password</label>
            <input class='form-control' type="password" class="form-control" name="login_password" id="login_password" placeholder="" /><br><br>
          </div>
          <div class='row row-login'>
            <input type="submit" class="form-control" name="login" id="login_submit" placeholder="" value="Login" />
          </div>
          <a href="bitter_retrieve.php" id="reset">Forgot your password?</a>
        </form>

      </div>


    </div>
    <!--</div>-->

  </main>

  <script src="includes/resetModal.php"></script>
</body>

</html>
