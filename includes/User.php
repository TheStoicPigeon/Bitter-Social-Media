<?php

require_once("Time.php");

class User implements Follows
{

  private int $userId;
  private string $password;
  private string $firstName;
  private string $lastName;
  private string $province;
  private string $contactNo;
  private string $dateAdded;
  private string $location;
  private string $url;
  private string $userName;
  private string $address;
  private string $postalCode;
  private string $email;
  private string $profImage = User::DEFAULT_IMG;
  private string $description;
  private string $error;
  private $follows = array();
  private $followers = array();

  const IMAGE_PATH = "images/profilepics/";
  const DEFAULT_IMG = "default.jfif";

  use Time;


  public function stringifyFollows()
  {
    return json_encode($this->follows);
  }

  //------------------------------MUTATORS------------------------------


  public function __get($prop)
  {
    return $this->$prop;
  }

  public function __set($prop, $value)
  {
    if ($prop == "userId")
      return;
    $this->$prop = sanitizeSQL($value);
  }

  public function __destruct()
  {
  }


  //------------------------------PASSWORDS------------------------------

  public function setPassword($pass)
  {
    $this->password = password_hash($pass, PASSWORD_BCRYPT);
  }

  // private function verifyPassword($password)
  private function verifyPassword($password, $usersPassword)
  {
    // if (password_verify($password, $this->password))
    if (password_verify($password, $usersPassword))
      return true;
    return false;
  }



  //------------------------------CLASS FUNCTIONS------------------------------


  public static function ChangeUsername(string $username, int $userId): bool
  {
    global $con;

    $stmt = $con->prepare("UPDATE `users` SET `screen_name` = ? WHERE `user_id` = ?");
    $stmt->bind_param('si', $username, $userId);
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }




  public static function DeleteUser(int $userId): bool
  {
    global $con;

    $stmt = $con->prepare("DELETE from `users` where `user_id` = $userId");
    try {
      $stmt->execute();
      return $stmt->affected_rows == 1;
    } catch (Exception $e) {
      $con->rollback();
      return false;
    }
  }

  public static function UpdateUser(
    int $userId,
    string $f,
    string $l,
    string $e,
    string $p,
    string $a,
    string $prov,
    string $post,
    string $url,
    string $desc,
    string $loc
  ): bool {
    global $con;
    $stmt = $con->prepare("UPDATE `users` SET `first_name` = ?, `last_name` = ?, `email` = ?, `contact_number` = ?,
      `address` = ?, `province` = ?, `postal_code` = ?, `url` = ?, `description` = ?, `location` = ?
      WHERE `user_id` = ?");

    $stmt->bind_param('ssssssssssi', $f, $l, $e, $p, $a, $prov, $post, $url, $desc, $loc, $userId);
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }

  public static function AddUser(User $user): bool
  {
    global $con;

    $stmt = $con->prepare(
      "INSERT INTO `users`
      (`first_name`, `last_name`, `screen_name`, `password`, `address`, `province`,
      `postal_code`, `contact_number`, `email`, `url`, `description`, `location`, `profile_pic`)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"
    );

    $stmt->bind_param(
      'sssssssssssss',
      $user->firstName,
      $user->lastName,
      $user->userName,
      $user->password,
      $user->address,
      $user->province,
      $user->postalCode,
      $user->contactNo,
      $user->email,
      $user->url,
      $user->description,
      $user->location,
      $user->profImage
    );


    $stmt->execute();

    if ($stmt->affected_rows == 1) {
      return true;
    } else { //something went wrong
      return false;
    }
  }


  //Called from DirectMessage_proc <-- GetConversations() in DMModal.js
  //Get the LAST messages to the userId supplied 
  //Generates a clickable div containing the userId that will be handled by ConversationSelected in DMModal.j
  public static function GetConversations(int $userId)
  {
    global $con;

    $stmt = $con->prepare("select distinct `first_name`, `last_name`, `message_text`, `screen_name`, `profile_pic`, `from_id`, `to_id`, TIMEDIFF(NOW(),`date_sent`)
                            FROM `messages` 
                            INNER JOIN `users`
                            ON `user_id` = `from_id`
                            WHERE `to_id` = ? 
                            AND `id` IN (select max(id)
                                        from `messages` 
                                        where `to_id` = ?
                                        group by `from_id`)");

    $stmt->bind_param('ii', $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = "";
    while (list($first, $last, $msg, $screenName, $profImage, $fromId, $toId, $time) = $result->fetch_row()) {

      //create a new user for each person in our converstations to make it easier to pass them around
      $dm_user = new User();
      $dm_user->firstName = $first;
      $dm_user->lastName = $last;
      $dm_user->userName = $screenName;
      $dm_user->userId = $fromId == $userId ? $toId : $fromId;
      $dm_user->profImage = USER::IMAGE_PATH . $profImage;

      $time = self::DisplayDate($time);
      $response .= <<<_MSG
        
      <div class="conversation" id="conversation_{$dm_user->userId}" data-id="$dm_user->userId" onClick="((e) => {
      ConversationSelected($dm_user->userId)})()">
      <div class="img_wrapper">
          <div class='profile_image_container'>
            <img src="{$dm_user->profImage}" class='profile_image'/>
          </div>
        </div>
        <div class='text_wrapper'>
          <div class="msg_header">{$dm_user->getHandle()}<span class='time'>$time</span></div>
          <div class="msg_text">$msg</div>
        </div>
      </div>
      _MSG;
    }
    return $response;
  }


  //Get all messages from a specific conversation
  public static function GetMessages(int $uId, int $rId)
  {
    global $con;

    $stmt = $con->prepare("SELECT `user_id`,`message_text`, `date_sent`, `profile_pic` 
      FROM `messages` 
      INNER JOIN `users`
      ON `from_id` = `user_id`
      WHERE (`to_id` = ? AND `from_id` = ?) 
      OR (`to_id` = ? AND `from_id` = ?)
      ORDER BY TIMEDIFF(`date_sent`, NOW())");
    $stmt->bind_param('iiii', $uId, $rId, $rId, $uId);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = "<div class='conversation_container'>";
    while (list($userId, $text, $date, $pic) = $result->fetch_row()) {

      //only show profile_pic for the recipient
      //change the output based on who it is sent by
      if ($userId != $uId) {

        $profImage = USER::IMAGE_PATH . $pic;

        $response .= <<<_MSG

        <div class='message_wrapper_recipient'>
          <div class="img_wrapper">
            <div class='profile_image_container'>
              <img src="$profImage" class='profile_image'/>
            </div>
          </div>
          <div class='message'>
            <div class="msg_text">$text</div>
          </div>
        </div>

      _MSG;
      } else {
        $response .= <<<_MSG
        <div class='message_wrapper_host'>
          <div class='message'>
            <div class="msg_text">$text</div>
          </div>
        </div>
      _MSG;
      }
    } //end while
    $response .= "</div>";
    return $response;
  } //end while


  public static function SendMessage(int $sender, int $recipient, string $text)
  {

    global $con;

    $stmt = $con->prepare("INSERT INTO `messages` (`from_id`, `to_id`, `message_text`, `date_sent`) VALUES (?,?,?, NOW())");
    $stmt->bind_param('iis', $sender, $recipient, sanitizeSQL($text));
    $stmt->execute();
  }


  public static function SearchUsers(string $query, User $user)
  {
    global $con;

    $q = "%" . $con->real_escape_string($query) . "%";
    $stmt = $con->prepare("SELECT `user_id`, `first_name`, `last_name`, `screen_name`, `profile_pic`  
      FROM `users` 
      WHERE (`first_name` LIKE ? 
      or `last_name` LIKE ? 
      or screen_name LIKE ?)
      and `user_id` != $user->userId");

    $stmt->bind_param('sss', $q, $q, $q);
    $stmt->execute();

    $result = $stmt->get_result();

    $response = "";

    if ($result->num_rows > 0) {

      $total = $result->num_rows;

      $response .= <<<_END
        <h4>Users found: $total</h4>
        <div class='container search-container'>
          <form method='post' action='Follow_proc.php'><ul class='list-unstyled'>
      _END;

      $currentFollows = $user->getFollows();

      while (list($id, $first, $last, $screenName, $pic) = $result->fetch_row()) {
        //check to see if they aren't in the users follows array
        $person = new User();
        $person->userId = $id;
        $person->firstName = $first;
        $person->lastName = $last;
        $person->userName = $screenName;
        $person->profImage = User::IMAGE_PATH . $pic;

        $response .= <<<_END
          <div class='row row-whoToTroll' style='width:max-content'>
            <a id="tweeter" href='userpage.php?user=$person->userId'>{$person->getHandleShort()}</a>
          _END;


        //check to see if person isn't yet followed
        $res = array_search($id, $currentFollows);
        if ($res === false) {
          $response .= "<button type='submit' style='margin-left:1rem' name='follow' value='{$person->userId}|{$person->userName}' class='btn btn-outline-primary btn-sm'>Follow</button>";

          //if they are followed display follow button
        } else {
          $response .= "<span class='black'> | Following</span>";
        }
        if (array_search($user->userId, $person->follows)) {
          $response .= "<span class='black'> | Follows You</span>";
        }

        $response .= "</div>";
      } //end while 
      $response .= "</ul></form></div>";
    } //end if $results
    return $response;
  } //end SearchUsers



  //return true if no users exist in db with given username
  //used by singup-validation ajax
  public static function CheckUsername($username): bool
  {
    global $con;
    $stmt = $con->prepare("SELECT count(`screen_name`) from `users` WHERE `screen_name` = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] == 0;
  }

  public static function GetUsers(string $query, int $userId, bool $option)
  {
    global $con;

    if ($option == true) { //get all matches
      $stmt = $con->prepare("SELECT `user_id`, `first_name`, `last_name`, `screen_name`, `profile_pic`
        from `users` 
        WHERE `screen_name` LIKE '%$query%' 
        AND `user_id` != $userId");
    } else { //only get matches who the user follows
      $stmt = $con->prepare("SELECT `user_id`, `first_name`, `last_name`, `screen_name`, `profile_pic`
        from `users` 
        WHERE `screen_name` LIKE '%$query%' 
        AND `user_id` != $userId
        AND `user_id` IN (SELECT `to_id` from `follows` WHERE `from_id` = $userId)");
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $response = "";

    while (list($id, $first, $last, $screenName, $pic) = $result->fetch_row()) {
      $dm_user = new User();
      $dm_user->userId = $id;
      $dm_user->firstName = $first;
      $dm_user->lastName = $last;
      $dm_user->userName = $screenName;
      $dm_user->profImage = USER::IMAGE_PATH . $pic;

      $response .= <<<_MSG
        
      <div class="conversation" id="conversation_{$dm_user->userId}" data-id="$dm_user->userId" onClick="((e) => {
      ConversationSelected($dm_user->userId)})()">
      <div class="img_wrapper">
          <div class='profile_image_container'>
            <img src="{$dm_user->profImage}" class='profile_image'/>
          </div>
        </div>
        <div class='text_wrapper'>
          <div class="msg_header">{$dm_user->getHandle()}</div>
        </div>
      </div>
      _MSG;
    }
    return $response;
  }


  public static function VerifyEmail($email): bool
  {
    global $con;
    $stmt = $con->prepare("Select `email` from `users` WHERE `email` = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->num_rows == 1;
  }


  public static function GetUserEmail($userId): string
  {
    global $con;
    $stmt = $con->prepare("Select `email` from `users` WHERE `user_id` = $userId");
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? null;
  }

  public static function GetUserByEmail($email)
  {

    global $con;

    $stmt = $con->prepare("Select `user_id` from `users` WHERE `email` = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? -1;
  }


  public static function GetUserById($userId)
  {

    global $con;

    $stmt = $con->prepare("SELECT `first_name`, `last_name`, `password`, `screen_name`, `profile_pic`, `province`, `date_created`
      from `users` 
      WHERE `user_id` = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows == 1) {

      while ($row = $result->fetch_object()) {
        $user = new User();
        $user->userId = $userId;
        $user->firstName = $row->first_name;
        $user->lastName = $row->last_name;
        $user->userName = $row->screen_name;
        $user->province = $row->province;
        $user->profImage = self::IMAGE_PATH . $row->profile_pic;
        $user->dateAdded = $row->date_created;
        return $user;
      }
    } else {
      return false;
    }
  }


  //Used by login_proc
  //Creates a User instance if screenName exists on DB
  //returns User instance or false if the screen-name doesn't exist on the DB
  public static function GetUser($username, $password)
  {
    global $con;

    $stmt = $con->prepare("SELECT `user_id`, `first_name`, `last_name`, `password`, `screen_name`, `profile_pic`, `province`, `date_created`
      from `users` 
      WHERE `screen_name` = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();


    $result = $stmt->get_result();
    if ($result) {

      while ($row = $result->fetch_object()) {
        $user = new User();
        $user->userId = $row->user_id;
        $user->firstName = $row->first_name;
        $user->lastName = $row->last_name;
        $user->userName = $row->screen_name;
        $user->province = $row->province;
        $user->profImage = self::IMAGE_PATH . $row->profile_pic;
        $user->dateAdded = $row->date_created;

        $userPass = $row->password;

        if ($user->verifyPassword($password, $userPass)) {
          return $user;
        } else {
          return "invalid password";
        }
      }
      $stmt->close();
    }
    return false;
  }



  public static function GetTempUser($userId)
  {
    global $con;

    $stmt = $con->prepare("SELECT * from `users` WHERE `user_id` = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();


    $result = $stmt->get_result();
    if ($result) {

      while ($row = $result->fetch_object()) {
        $user = new User();
        $user->userId = $row->user_id;
        $user->firstName = $row->first_name;
        $user->lastName = $row->last_name;
        $user->userName = $row->screen_name;
        $user->email = $row->email;
        $user->password = $row->password;
        $user->contactNo = $row->contact_number;
        $user->address = $row->address;
        $user->province = $row->province;
        $user->postalCode = $row->postal_code;
        $user->url = $row->url;
        $user->description = $row->description;
        $user->location = $row->location;
        $user->profImage = self::IMAGE_PATH . $row->profile_pic;
        $user->dateAdded = $row->date_created;


        $user->getFollows();
      }
      $stmt->close();
      return $user;
    }
    return false;
  }



  //Takes in a User and displays a div containing a form with three random Users that aren't being followed yet
  public static function whoToTroll(int $userId): string
  {
    global $con;

    $stmt = $con->prepare("SELECT `user_id`, `first_name`, `last_name`, `screen_name`, `profile_pic` 
                          FROM `users` where `user_id` != $userId 
                          AND `user_id` NOT IN
                          (SELECT `to_id` FROM `follows` where `from_id` = $userId) 
                          ORDER BY RAND() LIMIT 3");
    $stmt->execute();

    $result = $stmt->get_result();

    $response = "";
    $response .= "<form method='post' action='Follow_proc.php'>";
    $response .= "<ul class='list-unstyled' style='color:blue;' >";

    if ($result) {
      $people = $result->fetch_all(MYSQLI_ASSOC);
      $response .= "<div class='container container-whoToTroll'>";
      foreach ($people as $row) {
        $person = new User();
        $person->userId = $row['user_id'];
        $person->firstName = $row['first_name'];
        $person->lastName = $row['last_name'];
        $person->userName = $row['screen_name'];
        $person->profImage = User::IMAGE_PATH . $row['profile_pic'];

        $response .= <<<_END
          <div class='row row-whoToTroll animate__animated animate__flipInX'> <img class='profile-icon' src='$person->profImage'><p class='text-primary d-inline'><a href='userpage.php?user=$person->userId'>{$person->getHandleShort()}</a></p></br>
          <button type='submit' name='follow' value='{$person->userId}|{$person->userName}' class='btn btn-outline-primary btn-sm'>Follow</button> </div>
        <hr>
        _END;
      }
      $response .= "</div></ul></form>";

      $stmt->close();
    }
    return $response;
  }




  public static function FollowersYouKnow(User $temp, User $user)
  {
    global $con;

    $set = array_intersect($temp->follows, $user->follows);
    $count = count($set);
    $ids = $count > 0 ? implode(",", array_keys($set)) : 0;

    $stmt = $con->prepare("SELECT `user_id`, `first_name`, `last_name`, `screen_name`, `profile_pic`
                            FROM `users`
                            WHERE `user_id` in ($ids)
                            ORDER BY RAND() LIMIT 3");

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
      echo "<div class='bold'>$count Followers you know<BR></div>";
      echo "<div class='container container-follows'>";

      $people = $result->fetch_all(MYSQLI_ASSOC);
      foreach ($people as $row) {
        $person = new User();
        $person->userId = $row['user_id'];
        $person->firstName = $row['first_name'];
        $person->lastName = $row['last_name'];
        $person->userName = $row['screen_name'];
        $person->profImage = User::IMAGE_PATH . $row['profile_pic'];

        echo <<<_END
        <div class='row row-followersYouKnow'>
          <img class='profile-icon' src='$person->profImage'>
          <div class='row-follower'>
            <a href='userpage.php?user=$person->userId'><b>{$person->getFullName()}</b>
            <span class='handle'>@{$person->userName}</span></a>
          </div>
        </div>
        _END;
      }
      echo "</div>";

      $stmt->close();
    }
  }



  //------------------------------HELPER FUNCTIONS------------------------------

  public function getHandleShort(): string
  {
    return substr(("<b>" . $this->userName . "</b>  @ " . $this->firstName . $this->lastName), 0, 33);
  }

  public function getHandle(): string
  {
    return "<b>" . $this->userName . "</b>  @ " . $this->firstName . $this->lastName;
  }
  // public function getHandleShortest(): string
  // {
  //   return "<b>" . $this->userName . "</b>  @ " . substr($this->firstName, 0, 1);
  // }


  public function getFullName(): string
  {
    return ucwords($this->firstName . ' ' . $this->lastName);
  }


  public function getCountTweets()
  {
    global $con;
    $stmt = $con->prepare("SELECT count(*) FROM `tweets` WHERE `user_id` = $this->userId");
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? 0;
  }

  public function getCountFollows()
  {
    global $con;
    $stmt = $con->prepare("SELECT count(*) FROM `follows` WHERE `from_id` = $this->userId");
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? 0;
    // return count($this->follows);
  }

  public function getCountFollowers()
  {
    global $con;
    $stmt = $con->prepare("SELECT count(*) FROM `follows` WHERE `to_id` = $this->userId");
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? 0;
  }


  public function updateProfilePic(string $path): bool
  {
    global $con;

    $stmt = $con->prepare("UPDATE `users` SET `profile_pic` = ? WHERE `user_id` = $this->userId");

    $stmt->bind_param('s', $path);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {
      if (strcmp($this->profImage, "images/profilepics/default.jfif") != 0) {
        $oldImage = $this->profImage;
      }
      $this->profImage = self::IMAGE_PATH . $path;
      unlink($oldImage);
      return true;
    }
    return false;
  }


  public static function UpdatePassword(string $pass, int $userId): bool
  {

    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    global $con;

    $stmt = $con->prepare("UPDATE `users` SET `password` = ? WHERE `user_id` = ?");
    $stmt->bind_param('si', $hashed_pass, $userId);
    $stmt->execute();
    return $stmt->affected_rows == 1;
  }


  //------------------------------INTERFACE IMPLEMENTATION------------------------------

  public function followUser(int $id, string $name)
  {
    // $this->follows[$id] = $name;

    global $con;

    $query = "INSERT INTO `follows`(`from_id`, `to_id`) VALUES ($this->userId, $id)";
    mysqli_query($con, $query);

    // $this->follows[$id] = $name;
    return $con->affected_rows;
  }

  public function unfollowUser(User $user)
  {
    unset($this->follows[$user->userId]);
  }



  private function getFollows()
  {
    global $con;
    $stmt = $con->prepare("SELECT `to_id`
      FROM `follows` 
      INNER join `users` 
      ON `to_id` = `user_id` 
      WHERE `from_id` = ?");

    $stmt->bind_param('i', $this->userId);
    $stmt->execute();
    $result = $stmt->get_result();


    $follows = array(); //added this

    $ids = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($ids as $id) {
      $follows[] = $id['to_id'];
    }
    $stmt->close();
    return $follows; //added this
  }
} //end User
