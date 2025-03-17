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