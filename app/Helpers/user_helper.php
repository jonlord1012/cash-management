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

if (!function_exists('getBranchShortNameByBranchCode')) {
   function getBranchShortNameByBranchCode($branchCode)
   {
      $userModel = new \App\Models\BranchModel();
      $branchName = $userModel->where('branch_code', $branchCode)->first();
      return $branchName ? $branchName['short_name'] : 'N/A';
   }
}

if (!function_exists('getBranchNameByBranchCode')) {
   function getBranchNameByBranchCode($branchCode)
   {
      $userModel = new \App\Models\BranchModel();
      $branchName = $userModel->where('branch_code', $branchCode)->first();
      return $branchName ? $branchName['name'] : 'N/A';
   }
}

if (!function_exists('getIsHeadOffice')) {
   function getIsHeadOffice($branchCode)
   {
      $userModel = new \App\Models\BranchModel();
      $is_head_office = $userModel->where('branch_code', $branchCode)->first();
      return $is_head_office ? $is_head_office['is_head_office'] : '0';
   }
}

if (!function_exists('validateDataTableRequest')) {
   function validateDataTablesRequest($request)
   {
      $required = ['draw', 'start', 'length'];
      foreach ($required as $param) {
         if (!isset($request[$param])) {
            throw new InvalidArgumentException("Missing $param parameter");
         }
      }
   }
}