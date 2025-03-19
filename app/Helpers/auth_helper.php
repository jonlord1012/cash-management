<?php

if (!function_exists('auth')) {
   /**
    * Get the Auth library instance
    * @return \App\Libraries\Auth
    */
   function auth()
   {
      return \Config\Services::auth();
   }
}


if (!function_exists('has_access')) {
   function has_access($requiredRole)
   {
      $auth = service('auth');
      if (!$auth->check()) return false;
      return $auth->user()['user_group'] === $requiredRole;
   }
}

if (!function_exists('is_superadmin')) {
   function is_superadmin()
   {
      return has_access('superadmin');
   }
}