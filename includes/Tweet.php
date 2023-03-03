<?php

require_once("Time.php");
class Tweet
{
  private int $tweetId;
  private int $userId;
  private int $originalTweetId = 0;
  private int $replyToTweetId = 0;
  private int $likes = 0;
  private string $dateAdded;
  private string $tweetText;
  private string $name;

  use Time;

  public function __get($name)
  {
    return $this->$name;
  }

  public function __set($name, $value)
  {
    $this->$name = $value;
  }

  public function __construct()
  {
    $this->dateAdded = time();
  }

  public function __destruct()
  {
  }


  public static function getTrendingByLikes()
  {
    global $con;
    //get the tweets that have most likes in the past 5 days
    $stmt = $con->prepare("select count(`likes`.`tweet_id`), `likes`.`tweet_id`, `tweets`.`tweet_text`, `users`.`user_id`, `users`.`first_name`, `users`.`last_name`, `users`.`screen_name`
                          from `likes`
                          inner join `tweets`
                          on `likes`.`tweet_id` = `tweets`.`tweet_id`
                          inner join `users` 
                          on `tweets`.`user_id` = `users`.`user_id`
                          group by `likes`.`tweet_id`
                          having count(`likes`.`tweet_id`) = (select max(x.liked)
                                                              from 
                                                              (
                                                                  select count(tweet_id) liked 
                                                                  from `likes` 
                                                                  group by `tweet_id`
                                                              ) x )
                          ORDER BY `likes`.`date_created` LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

      $response = "<div class='top_trender'>";

      list($likes, $tweet_id, $text, $user_id, $first, $last, $username) = $result->fetch_row();

      $tweet = new Tweet();
      $tweet->userId = $user_id;
      $tweet->tweetId = $tweet_id;
      $tweet->tweetText = stripslashes($text);
      $tweet->name = ucwords($first . " " . $last);

      $data = self::toJSON($tweet);

      $response .= self::displayTrending($tweet, $data, $username);
      return $response;
    }
  }

  public static function getTrendingByRetweets()
  {
    global $con;
    //get the tweets that have most likes in the past 5 days
    $stmt = $con->prepare("select count(`tweets`.`original_tweet_id`), `tweets`.`tweet_id`, `tweets`.`tweet_text`, `users`.`user_id`, `users`.`first_name`, `users`.`last_name`, `users`.`screen_name`
                          from `tweets`
                          inner join `users` 
                          on `tweets`.`user_id` = `users`.`user_id`
                          group by `tweets`.`original_tweet_id`
                          having count(`tweets`.`original_tweet_id`) = (select max(x.retweeted)
                                                              from 
                                                              (
                                                                  select count(original_tweet_id) retweeted 
                                                                  from `tweets` 
                                                                  where `original_tweet_id` != 0
                                                                  group by `original_tweet_id`
                                                              ) x )
                          ORDER BY `tweets`.`original_tweet_id` LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

      $response = "<div class='top_trender'>";

      list($retweets, $tweet_id, $text, $user_id, $first, $last, $username) = $result->fetch_row();

      $tweet = new Tweet();
      $tweet->userId = $user_id;
      $tweet->tweetId = $tweet_id;
      $tweet->tweetText = stripslashes($text);
      $tweet->name = ucwords($first . " " . $last);

      $data = self::toJSON($tweet);

      $response .= self::displayTrending($tweet, $data, $username);
      return $response;
    }
  }



  public static function getTrendingByReplies()
  {
    global $con;
    //get the tweets that have most likes in the past 5 days
    $stmt = $con->prepare("select count(`tweets`.`reply_to_tweet_id`),`tweets`.`tweet_id`, `tweets`.`tweet_text`, `users`.`user_id`, `users`.`first_name`, `users`.`last_name`, `users`.`screen_name`
                          from `tweets`
                          inner join `users` 
                          on `tweets`.`user_id` = `users`.`user_id`
                          group by `tweets`.`reply_to_tweet_id`
                          having count(`tweets`.`original_tweet_id`) = (select max(x.replied)
                                                              from 
                                                              (
                                                                  select count(original_tweet_id) replied
                                                                  from `tweets` 
                                                                  where `reply_to_tweet_id` != 0
                                                                  group by `reply_to_tweet_id`
                                                              ) x )
                          ORDER BY `tweets`.`reply_to_tweet_id` LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

      $response = "<div class='top_trender'>";

      list($replies, $tweet_id, $text, $user_id, $first, $last, $username) = $result->fetch_row();

      $tweet = new Tweet();
      $tweet->userId = $user_id;
      $tweet->tweetId = $tweet_id;
      $tweet->tweetText = stripslashes($text);
      $tweet->name = ucwords($first . " " . $last);

      $data = self::toJSON($tweet);

      $response .= self::displayTrending($tweet, $data, $username);
      return $response;
    }
  }


  public static function CheckLiked(int $tweetId, int $userId): int
  {
    global $con;

    $stmt = $con->prepare("SELECT count(*) FROM `likes` WHERE `tweet_id` = $tweetId AND `user_id` = $userId");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    return $result;
  }



  public static function LikeTweet(int $tweetId, int $userId): int
  {
    global $con;

    $stmt = $con->prepare("INSERT INTO `likes` (`tweet_id`, `user_id`, `date_created`) VALUES (?,?,NOW())");

    // $date = new DateTime();
    $stmt->bind_param('ii', $tweetId, $userId);
    $stmt->execute();
    $result = $stmt->affected_rows;
    $stmt->close();

    return $result;
  }



  public function getCountLiked(): int
  {
    global $con;

    $stmt = $con->prepare("SELECT count(*) from `likes` WHERE `tweet_id` = $this->tweetId");
    $stmt->execute();
    $this->likes = $stmt->get_result()->fetch_row()[0] ?? 0;
    return $this->likes;
  }

  public function getCountReplies(): int
  {
    global $con;

    $stmt = $con->prepare("SELECT count(*) from `tweets` WHERE `reply_to_tweet_id` = $this->tweetId");
    $stmt->execute();
    $this->replies = $stmt->get_result()->fetch_row()[0] ?? 0;
    return $this->replies;
  }


  public function getCountRetweeted(): int
  {
    global $con;

    $stmt = $con->prepare("SELECT count(*) from `tweets` WHERE `original_tweet_id` = $this->tweetId");
    $stmt->execute();
    $this->retweets = $stmt->get_result()->fetch_row()[0] ?? 0;
    return $this->retweets;
  }


  public static function SearchTweets(string $query, User $user)
  {

    global $con;

    $stmt = $con->prepare("SELECT `first_name`, `last_name`,
        `screen_name`, `tweet_id`, `tweets`.`user_id`, `tweet_text`,
        `original_tweet_id`, `reply_to_tweet_id`, TIMEDIFF(NOW(), `tweets`.`date_created`) 
        From `users` 
        INNER JOIN `tweets` 
        on `users`.`user_id` = `tweets`.`user_id` 
      WHERE `tweets`.`tweet_text` LIKE '%$query%' 
      AND `tweets`.`user_id` != $user->userId
        ORDER BY TIMEDIFF(NOW(), `tweets`.`date_created`)
        LIMIT 10");

    $stmt->execute();
    $result = $stmt->get_result();

    $response = '';

    if ($result->num_rows > 0) {

      $total = $result->num_rows;

      $response .= "<h4 style='margin-bottom:2rem'>Tweets found: $total</h4>";

      $count = 0;
      $colors = ['#f7f7fa', '#ffffff'];
      while (list($first, $last, $screenName, $t_id, $t_user_id, $t_text, $t_orig_id, $t_reply_id, $t_date) = $result->fetch_row()) {

        $tweet = new Tweet();
        $tweet->userId = $t_user_id;
        $tweet->tweetId = $t_id;
        $tweet->originalTweetId = $t_orig_id;
        $tweet->replyToTweetId = $t_reply_id;
        $tweet->dateAdded = $t_date;
        $tweet->tweetText = stripslashes($t_text);
        $tweet->name = ucwords($first . " " . $last);

        $tweet->getCountLiked();
        $tweet->getCountRetweeted();
        $tweet->getCountReplies();

        $displayDate = self::DisplayDate($tweet->dateAdded);

        $heading = ($tweet->originalTweetId == 0) ? $displayDate : $displayDate . self::GetOriginal($tweet->originalTweetId);

        $data = self::toJSON($tweet);

        $response .= "<div id='search-tweet' style='background-color:{$colors[$count % 2]}'>";
        $response .= self::displayTweet($tweet, $data, $heading, $screenName);
        $response .= "</div>";
        $count++;
      } //end while
    } //end if result
    return $response;
  } //end SearchTweets




  public static function PostTweet(Tweet $tweet): int
  {
    global $con;

    $stmt = $con->prepare("INSERT INTO `tweets`(`tweet_text`, `user_id`, `original_tweet_id`, `reply_to_tweet_id`, `date_created`) VALUES (?,?,?,?,FROM_UNIXTIME(?))");

    $stmt->bind_param('ssiii', $tweet->tweetText, $tweet->userId, $tweet->originalTweetId, $tweet->replyToTweetId, $tweet->dateAdded);
    $stmt->execute();
    $result = $stmt->affected_rows;
    $stmt->close();

    return $result;
  }


  //Get Notifications
  public static function GetNotifications(int $userId)
  {
    global $con;

    $stmt = $con->prepare("SELECT `likes`.`user_id`, TIMEDIFF(NOW(), `likes`.`date_created`), 
      `users`.`first_name`, `users`.`last_name`, `users`.`screen_name`, `users`.`profile_pic`, 
      `tweets`.`tweet_text`, `tweets`.`tweet_id`, `tweets`.`original_tweet_id`
      FROM `likes`
      INNER JOIN `users`
      ON `likes`.`user_id` = `users`.`user_id`
      INNER JOIN `tweets`
      ON `tweets`.`tweet_id` = `likes`.`tweet_id`
      WHERE `likes`.`tweet_id` IN
        (SELECT `tweets`.`tweet_id` 
        FROM `tweets`
        WHERE `tweets`.`user_id` = ?)
     ORDER BY TIMEDIFF(NOW(), `likes`.`date_created`)
      ");
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $count = 0;
    $colors = ['#f7f7fa', 'white'];

    $response = "<div class='notifications'>";
    while (list($userId, $date, $first, $last, $screenName, $pic, $text, $t_id, $t_orig) = $result->fetch_row()) {
      $count++;
      $tweet = new Tweet();
      $tweet->userId = $userId;
      $tweet->tweetId = $t_id;
      $tweet->originalTweetId = $t_orig;
      $tweet->tweetText = stripslashes($text);
      $tweet->name = ucwords($first . " " . $last);
      $tweet->profImage = "images/profilepics/" . $pic;
      $likedOn = $date;

      $displayDate = self::DisplayDate($likedOn);

      $retweeted = ($tweet->originalTweetId != 0) ? self::GetOriginal($tweet->originalTweetId) : "";

      $data = self::toJSON($tweet);

      $response .= <<<_END
              <div class='row-header'>
                <div class='row row-title'>
                  <div class='profile_image_container'>
                    <img src="{$tweet->profImage}" class='profile_image'/>
                  </div>
                  <a class="tweeter" href='userpage.php?user=$tweet->userId'>
                  <span>$tweet->name</span></a>&nbsp;liked your tweet $displayDate
                </div>
              </div>
              <div class='row row-text'>
                <h6>$retweeted</h6>
                <p> $tweet->tweetText</p>
              </div>
              <hr>
          _END;
    } //end while
    $response .= "</div>";
    return $response;
  }



  public static function GetTweets(int $userId, $tmp = false)
  {
    global $con;

    if (!$tmp) {

      $stmt = $con->prepare("SELECT `first_name`, `last_name`,
       `screen_name`, `tweet_id`, `tweets`.`user_id`, `tweet_text`,
       `original_tweet_id`, `reply_to_tweet_id`, TIMEDIFF(NOW(), `tweets`.`date_created`)
         From `users`
         INNER JOIN `tweets`
         on `users`.`user_id` = `tweets`.`user_id`
         WHERE `users`.`user_id` in (SELECT `user_id` 
                                    FROM `users` where `user_id` != $userId 
                                    AND `user_id` IN
                                    (SELECT `to_id` FROM `follows` where `from_id` = $userId))
         OR `users`.`user_id` = $userId
         and `tweets`.`reply_to_tweet_id` = 0
         ORDER BY TIMEDIFF(NOW(), `tweets`.`date_created`)
         LIMIT 10");
    } else {

      $stmt = $con->prepare("SELECT `first_name`, `last_name`,
      `screen_name`, `tweet_id`, `tweets`.`user_id`, `tweet_text`,
      `original_tweet_id`, `reply_to_tweet_id`, TIMEDIFF(NOW(), `tweets`.`date_created`)
        From `users`
        INNER JOIN `tweets`
        on `users`.`user_id` = `tweets`.`user_id`
        WHERE `users`.`user_id` = $userId
        ORDER BY TIMEDIFF(NOW(), `tweets`.`date_created`)
        LIMIT 10");
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $count = 0;
    $colors = ['#f7f7fa', 'white'];

    while (list($first, $last, $screenName, $t_id, $t_user_id, $t_text, $t_orig_id, $t_reply_id, $t_date) = $result->fetch_row()) {
      $count++;
      $tweet = new Tweet();
      $tweet->userId = $t_user_id;
      $tweet->tweetId = $t_id;
      $tweet->originalTweetId = $t_orig_id;
      $tweet->replyToTweetId = $t_reply_id;
      $tweet->dateAdded = $t_date;
      $tweet->tweetText = stripslashes($t_text);
      $tweet->name = ucwords($first . " " . $last);

      $tweet->getCountLiked();
      $tweet->getCountRetweeted();
      $tweet->getCountReplies();

      $displayDate = self::DisplayDate($tweet->dateAdded);

      $heading = ($tweet->originalTweetId == 0) ? $displayDate : $displayDate . "&nbsp;" . self::GetOriginal($tweet->originalTweetId);

      $data = self::toJSON($tweet);

      echo "<div class='row orig-tweet' style='background-color:{$colors[$count % 2]}'>";
      print(self::displayTweet($tweet, $data, $heading, $screenName));

      //----------------------------REPLIES------------------------------------------------

      if ($count > 0 && !$tmp) {
        self::GetReplies($tweet);
      }
      echo "</div>";
    } //end while
  } //end GetTweets


  private static function GetReplies($tweet)
  {
    global $con;
    $colors = ['white', 'white'];

    $stmt = $con->prepare("SELECT `first_name`, `last_name`,
      `screen_name`, `tweet_id`, `tweets`.`user_id`, `tweet_text`,
      `original_tweet_id`, `reply_to_tweet_id`, TIMEDIFF(NOW(), `tweets`.`date_created`) 
        From `users` 
        INNER JOIN `tweets` 
        on `users`.`user_id` = `tweets`.`user_id` 
      WHERE `tweets`.`reply_to_tweet_id` = $tweet->tweetId 
        ORDER BY TIMEDIFF(NOW(), `tweets`.`date_created`)
        LIMIT 10");

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {

      echo "<div class='container container-replies'>";

      $index = 10;
      while (list($first, $last, $screenName, $t_id, $t_user_id, $t_text, $t_orig_id, $t_reply_id, $t_date) = $result->fetch_row()) {

        $tweet = new Tweet();
        $tweet->userId = $t_user_id;
        $tweet->tweetId = $t_id;
        $tweet->originalTweetId = $t_orig_id;
        $tweet->replyToTweetId = $t_reply_id;
        $tweet->dateAdded = $t_date;
        $tweet->tweetText = $t_text;
        $tweet->name = ucwords($first . " " . $last);

        $tweet->getCountLiked();
        $tweet->getCountRetweeted();
        $tweet->getCountReplies();

        $displayDate = self::DisplayDate($tweet->dateAdded);


        $heading = ($tweet->originalTweetId == 0) ? $displayDate : $displayDate . self::GetOriginal($tweet->originalTweetId);

        $data = self::toJSON($tweet);

        $index--;

        echo "<div class='row reply-tweet' style='z-index:{$index}; background-color:{$colors[$index % 2]}'>";
        print(self::displayTweet($tweet, $data, $heading, $screenName));
        self::GetReplies($tweet); //DFS for replies to replies . . . to replies  . . .to replies

        echo "</div>";
      } //end while
      echo "</div>";
    }
  } //end GetReplies



  private static function GetOriginal($id): string
  {
    global $con;

    $stmt = $con->prepare("SELECT `users`.`user_id`, `first_name`, `last_name`
        From `users` 
        INNER JOIN `tweets` 
        on `users`.`user_id` = `tweets`.`user_id` 
        WHERE `tweets`.`tweet_id` = $id");

    $stmt->execute();
    $result = $stmt->get_result()->fetch_row();
    return "<b>retweeted from <a href='userpage.php?user=$result[0]' style='color:black'>" . $result[1] . " " . $result[2] . "</a></b>";
  }


  public static function toJSON(Tweet $tweet)
  {
    return "$tweet->name|$tweet->userId|$tweet->tweetId|$tweet->originalTweetId|$tweet->replyToTweetId|$tweet->dateAdded|" . addslashes($tweet->tweetText);
  }


  private static function displayTweet(Tweet $tweet, $data, $heading, $screenName): String
  {
    $likes = $tweet->likes > 0 ? $tweet->likes : "";
    $retweets = $tweet->retweets > 0 ? $tweet->retweets : "";
    $replies = $tweet->replies > 0 ? $tweet->replies : "";
    $display = <<<_END
      <div class='row-header'>
        <div class='row-tag'>
          <a class="tweeter" href='userpage.php?user=$tweet->userId'><strong>{$tweet->name}</strong></a>
          <span id='handle'><b>@ $screenName</b></span>
        </div>
        <div class='timestamp'>$heading</div>
      </div>
        <div class='row row-text'>
          <p>$tweet->tweetText</p>
        </div>
        <div class='row icons-container'>
        <div class="row-icons">
          <a href="#" data-liked="$data"><img src='./images/like.svg' ></a>
          <span id='like_counter'>$likes</span>
        </div>
        <div class="row-icons">
          <a href="#" data-retweeted="$data"><img src="./images/retweet.svg" alt="retweet"></a>
          <span id='retweet_counter'>$retweets</span>
        </div>
        <div class="row-icons">
          <a href='#replyModal' data-toggle="modal" data-tweetInfo="$data"><img src='./images/reply.svg'></a>
          <span id='reply_counter'>$replies</span>
        </div>
      </div>
    _END;

    return $display;
  }

  private static function displayTrending($tweet, $data, $username): String
  {
    return <<<_TRENDING

            <div class='row-header'>
              <div class='row-tag'>
                <a class="tweeter" href='userpage.php?user=$tweet->userId'><strong>{$tweet->name}</strong></a>
                <span id='handle'><b>@ $username</b></span>
              </div>
            <div class='row icons-container'>
              <div class="row-icons">
                <a href="#" data-liked="$data"><img src='./images/like.svg' ></a>
              </div>
              <div class="row-icons">
                <a href="#" data-retweeted="$data"><img src="./images/retweet.svg" alt="retweet"></a>
              </div>
              <div class="row-icons">
                <a href='#replyModal' data-toggle="modal" data-tweetInfo="$data"><img src='./images/reply.svg'></a>
              </div>
            </div>
            </div>

            <div class='row row-text'>
              <p>$tweet->tweetText</p>
            </div>

          </div>
      _TRENDING;
  }
}//end Class


          //<a href="like_proc.php?tweet=$data"><img src='../images/like.svg' ></a>
//<a href="retweet.php?tweet=$data"><img src='../images/retweet.svg'></a>
