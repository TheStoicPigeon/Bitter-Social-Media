<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top" id="navbar-container">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>


  <div class="collapse navbar-collapse justify-content-center" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" data-hover"" title="Home" href="index.php">
          <img src="images/logo.svg" class="logo"><br>Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="notifications.php">
          <img class="bannericons" src="images/notifications.svg"><br>Notifications</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#dm_modal" data-toggle="modal" data-user="<?= $user->userId ?>">
          <img class="bannericons" src="images/messages.svg"><br>Messages</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#search_modal" data-toggle="modal">
          <img class="bannericons" src="images/search.svg"><br>Search</a>
      </li>

      <li class="nav-item dropdown right">
        <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img class="bannericons" src="images/account.svg"><br>Account </a>
        <div class="dropdown-menu" aria-labelledby="dropdown01">
          <a class="dropdown-item" href="logout.php?logMeOut='true'">Logout</a>
          <a class="dropdown-item" href="account.php">Manage Account</a>
          <!--<p id='edit_profile_pic'><a class="dropdown-item" href="#" data-toggle="modal" data-target="#profile_modal">Edit Profile Picture</a></p>-->
        </div>
      </li>
    </ul>
  </div>
</nav>
