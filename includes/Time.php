<?php

trait Time
{
  public static function DisplayDate($timestamp)
  {
    list($hours, $minutes, $seconds) = explode(":", $timestamp);

    $hours = (int) $hours;
    $minutes = (int) $minutes;
    $seconds = (int) $seconds;

    if ($hours > 0) {
      if ($hours >= 24) {
        $days = (int)($hours / 24);
        if ($days > 1) return "$days days ago";
        return "$days day ago";
      }
      if ($hours > 1) return "$hours hours ago";
      else return "$hours hour ago";
    }

    if ($minutes > 0) {
      if ($minutes > 1) {
        return "$minutes minutes ago";
      } else {
        return "$minutes minute ago";
      }
    }

    if ($seconds > 1) {
      return "$seconds seconds ago";
    } else if ($seconds == 0) {
      return "0 seconds ago";
    } else {
      return "$seconds second ago";
    }
  }
}
