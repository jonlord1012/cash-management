<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ZReportsNameModel;

class ZReports extends BaseController
{
   protected $model;
   protected $userLogin;
   protected $branchCode;
   protected $branchName;

   public function __construct()
   {
      $this->model = new ZReportsNameModel();
      $auth = service('auth');
      if ($auth->check()) {
         $this->userLogin = $auth->user()['username'];
         $this->branchName = getBranchNameByUserCode($this->userLogin);
         $this->branchCode = getBranchCodeByUserCode($this->userLogin);
      } else {
         // Redirect to login or throw exception
         throw new \RuntimeException('User not authenticated');
         return redirect()->to('/login');
      }
   }

   public function reportName()
   {
      $data = [
         'title' => 'Report Management ' . $this->branchName,
         'zreports_name' => $this->model->orderBy('report_name', 'asc')->findAll()
      ];

      return view('admin/zreport_management', $data);
   }

   public function reportNametoggle($id)
   {
      if ($this->model->toggleStatus($id, $this->userLogin)) {
         return redirect()->back()->with('success', 'Report  status updated');
      }
      return redirect()->back()->with('error', 'Failed to update Report status');
   }
   public function reportNameedit($id)
   {
      $zreports_name = $this->model->find($id);
      if (!$zreports_name) {
         return redirect()->to('/admin/rptname')->with('error', 'Report Name not found');
      }

      return view('admin/zreports_name_edit', [
         'zreports_name' => $zreports_name
      ]);
   }
   public function reportNameupdate($id)
   {
      $zreports_name = $this->model->find($id);
      if (!$zreports_name) {
         return redirect()->back()->with('error', 'Report Name not found');
      }

      $data = [
         'report_name' => $this->request->getPost('report_name'),
         'update_user' => $this->userLogin,
      ];

      if ($this->model->update($id, $data, $this->userLogin)) {
         return redirect()->to('/admin/rptname')->with('success', 'Report updated successfully');
      }

      return redirect()->back()->withInput()->with('errors', $this->model->errors());
   }
}