<?php

namespace App\Libraries;

use Config\Services;

class Auth
{
   /**
    * @var \App\Models\UserModel
    */
   protected $userModel;

   /**
    * @var \CodeIgniter\Session\Session
    */
   protected $session;

   public function __construct()
   {
      $this->userModel = new \App\Models\UserModel();
      $this->session = Services::session(); // Proper service locator
   }

   public function login($username, $password)
   {
      $user = $this->userModel->verifyCredentials($username, $password);
      if ($user) {
         $session = session();
         $session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'user_group' => $user['user_group'],
            'branch_code' => $user['branch_code'],
            'logged_in' => true
         ]);
         return true;
      }
      return false;
   }

   public function hasAccess($userGroup, $routeOptions)
   {
      $config = config('Auth');

      // Allow superadmin all access
      if ($userGroup === 'superadmin') {
         return true;
      }

      // Check route permissions
      if (isset($routeOptions['permission'])) {
         return in_array($userGroup, (array)$routeOptions['permission']);
      }

      // Default deny access
      return false;
   }

   public function logout()
   {
      session()->destroy();
   }

   public function check()
   {
      return session()->get('logged_in') ?? false;
   }

   public function user()
   {
      if ($this->check()) {
         return $this->userModel
            ->where('username', $this->session->get('username'))
            ->first();
      }
      return null;
   }
}