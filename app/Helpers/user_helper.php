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


if (!function_exists('getBranchNameByUserCode')) {
   function getBranchNameByUserCode($userCode)
   {
      $userModel = new \App\Models\UserModel();
      $branch_code = $userModel->where('username', $userCode)->first();
      $branch_code = $branch_code ? $branch_code['branch_code'] : NULL;

      if ($branch_code === NULL) return false;
      $branchModel = new \App\Models\BranchModel();
      $branch_name = $branchModel->where('branch_code', $branch_code)->first();
      return $branch_name ? $branch_name['name'] : 'N/A';
   }
}


if (!function_exists('getBranchCodeByUserCode')) {
   function getBranchCodeByUserCode($userCode)
   {
      $userModel = new \App\Models\UserModel();
      $branch_code = $userModel->where('username', $userCode)->first();
      return $branch_code ? $branch_code['branch_code'] : 'N/A';
   }
}