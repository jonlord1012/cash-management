<?php

namespace App\Controllers;

use App\Libraries\Auth;

class Transaction extends BaseController
{
   public function index()
   {
      if (service('auth')->check()) {
         return redirect()->to('/dashboard');
      }
      return view('auth/login');
   }

   public function authenticate()
   {
      $username = $this->request->getPost('username');
      $password = $this->request->getPost('password');

      if (service('auth')->login($username, $password)) {
         return redirect()->to('/admin/coa');
      }

      return redirect()->back()->withInput()->with('error', 'Invalid credentials');
   }

   public function logout()
   {
      service('auth')->logout();
      return redirect()->to('/login');
   }
}