<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/13 0013
 * Time: 10:34
 */

namespace App\Policies;

 use Illuminate\Auth\Access\HandlesAuthorization;
class Policy
{
  use HandlesAuthorization;

  public function __construct()
  {
        
  }

  public function before($user, $ability)
  {

      if ($user->isAdmin()) {
          return true;
      }
  }
}