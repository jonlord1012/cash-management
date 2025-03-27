<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
   protected $table = 'users';

   protected $primaryKey = 'username';

   protected $useAutoIncrement = false;
   protected $allowedFields = [
      'id',
      'username',
      'password',
      'user_group',
      'branch_code',
      'is_active'
   ];
   protected $beforeInsert = ['hashPassword'];
   protected $beforeUpdate = ['hashPassword'];

   protected $validationRules = [
      'id'          => 'permit_empty|is_natural_no_zero',
      'username' => 'required|max_length[75]|is_unique[users.username,id,{id}]',
      'user_group' => 'required|in_list[superadmin,ho_accountant,branch_accountant,auditor]',
      'branch_code' => 'permit_empty|string|min_length[4]|max_length[15]',
      'is_active' => 'permit_empty|in_list[0,1]'
   ];

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

   public function getUsersWithBranch()
   {
      return $this->select('users.*, branches.name as branch_name')
         ->join('branches', 'branches.branch_code = users.branch_code', 'left')
         ->findAll();
   }
}