<?php

function sanitizeString($var)
{
  $var = htmlentities(strip_tags(stripslashes($var)));
  return $var;
}

function sanitizeSQL($var)
{
  global $con;

  $var = $con->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}
