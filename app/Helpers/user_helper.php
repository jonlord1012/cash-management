<?php

if (!function_exists('getUserName')) {
   function getUserName($userId)
   {
      $userModel = new \App\Models\UserModel();
      $user = $userModel->find($userId);
      return $user ? $user['username'] : 'N/A';
   }
}


if (!function_exists('getUserNameByName')) {
   function getUserNameByName($userName)
   {
      $userModel = new \App\Models\UserModel();
      $user = $userModel->where('username', $userName)->first();
      return $user ? $user['username'] : 'N/A';
   }
}