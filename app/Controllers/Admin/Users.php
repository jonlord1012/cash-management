<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\BranchModel;

class Users extends BaseController
{
   protected $model;
   protected $branchModel;

   public function __construct()
   {
      $this->model = new UserModel();
      $this->branchModel = new BranchModel();

      // Only superadmin can access
      if (session('user_group') !== 'superadmin') {
         throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
   }

   public function index()
   {
      $data = [
         'title' => 'User Management',
         'users' => $this->model->getUsersWithBranch(),
         'branches' => $this->branchModel->findAll()
      ];
      return view('admin/user_management', $data);
   }

   public function save()
   {
      $userId = $this->request->getPost('id') ?? null;
      $validationRules = [
         'username' => [
            'label' => 'Username',
            'rules' => $userId ?
               "required|max_length[75]|is_unique[users.username,id,{$userId}]" :
               "required|max_length[75]|is_unique[users.username]"
         ],
         'user_group' => [
            'label' => 'User Role',
            'rules' => 'required|in_list[superadmin,ho_accountant,branch_accountant,auditor]'
         ],
         'branch_code' => [
            'label' => 'Branch Code',
            'rules' => 'permit_empty|string|min_length[4]|max_length[15]'
         ]
      ];

      if (!$userId) {
         $validationRules['password'] = [
            'label' => 'Password',
            'rules' => 'required|min_length[4]'
         ];
      }

      if (!$this->validate($validationRules)) {
         return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
      }

      if (!$this->validate($validationRules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'id' => $userId,
         'username' => $this->request->getPost('username'),
         'user_group' => $this->request->getPost('user_group'),
         'branch_code' => $this->request->getPost('branch_code'),
         'is_active' => $this->request->getPost('is_active') ? 1 : 0
      ];
      // Only update password if provided
      if ($this->request->getPost('password')) {
         $data['password'] = $this->request->getPost('password');
      }

      if ($this->model->save($data)) {
         return redirect()->to('/admin/users')->with('success', 'User saved successfully');
      }

      return redirect()->back()->withInput()->with('error', 'Failed to save user');
   }

   public function delete($id)
   {
      if ($this->model->delete($id)) {
         return redirect()->back()->with('success', 'User deleted successfully');
      }
      return redirect()->back()->with('error', 'Failed to delete user');
   }
}