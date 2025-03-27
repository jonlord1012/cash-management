<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BranchModel;

class Branches extends BaseController
{
   protected $model;
   protected $userLogin;
   public function __construct()
   {
      $this->model = new BranchModel();
      $auth = service('auth');
      if ($auth->check()) {
         $this->userLogin = $auth->user()['username'];
      } else {
         // Redirect to login or throw exception
         throw new \RuntimeException('User not authenticated');
         return redirect()->to('/login');
      }
   }

   public function index()
   {
      $data = [
         'title' => 'Branch Management ' . $this->userLogin,
         'branches' => $this->model->orderBy('is_head_office desc, branch_code asc')->findAll()
      ];

      return view('admin/branch_management', $data);
   }
   public function create()
   {
      return view('admin/branch_create');
   }

   public function save()
   {

      log_message('debug', 'Save method called with data: ' . print_r($this->request->getPost(), true));

      $validation = $this->validate([
         'branch_code' => 'required|min_length[4]|max_length[15]|is_unique[branches.branch_code]',
         'name' => 'required|max_length[255]',

      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'branch_code' => $this->request->getPost('branch_code'),
         'name' => $this->request->getPost('name'),
         'is_head_office' => $this->request->getPost('is_head_office') ?? 0
      ];

      if ($this->model->createBranch($data, $this->userLogin)) {
         return redirect()->to('/admin/branches')->with('success', 'Branch created successfully');
      }

      return redirect()->back()->withInput()->with('error', 'Failed to create branch');
   }

   public function toggle($id)
   {
      if ($this->model->toggleStatus($id, $this->userLogin)) {
         return redirect()->back()->with('success', 'Branch status updated');
      }
      return redirect()->back()->with('error', 'Failed to update branch status');
   }
   public function edit($id)
   {
      $branch = $this->model->find($id);
      if (!$branch) {
         return redirect()->to('/admin/branches')->with('error', 'Branch not found');
      }

      return view('admin/branch_edit', [
         'branch' => $branch
      ]);
   }

   public function update($id)
   {
      $branch = $this->model->find($id);
      if (!$branch) {
         return redirect()->back()->with('error', 'Branch not found');
      }

      $data = [
         'name' => $this->request->getPost('name'),
         'is_head_office' => $this->request->getPost('is_head_office') ? 1 : 0
      ];

      if ($this->model->update($id, $data, $this->userLogin)) {
         return redirect()->to('/admin/branches')->with('success', 'Branch updated successfully');
      }

      return redirect()->back()->withInput()->with('errors', $this->model->errors());
   }
}