<?php

interface Follows
{

  public function followUser(int $id, string $screenName);

  public function unfollowUser(User $user);
}
