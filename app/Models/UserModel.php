<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   protected $table = 'users';
   protected $allowedFields = ['username', 'password', 'user_group', 'branch_code'];
   protected $beforeInsert = ['hashPassword'];

   protected function hashPassword(array $data)
   {
      if (isset($data['data']['password'])) {
         $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
      }
      return $data;
   }

   public function verifyCredentials($username, $password)
   {
      $user = $this->where('username', $username)->first();
      if ($user && password_verify($password, $user['password'])) {
         return $user;
      }
      return false;
   }
}