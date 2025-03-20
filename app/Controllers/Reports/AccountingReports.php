<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;
use App\Models\BranchModel;

class AccountingReports extends BaseController
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
      helper('form');
   }

   public function summaryInput()
   {
      $data = [
         'title' => 'Branch Management ' . $this->userLogin,
         'branches' => $this->model->orderBy('is_head_office', 'DESC')->findAll()
      ];

      return view('reports/summary_input', $data);
   }

   public function hutangBank()
   {
      $data = [
         'title' => 'Branch Management ' . $this->userLogin,
         'branches' => $this->model->orderBy('is_head_office', 'DESC')->findAll()
      ];

      return view('reports/hutang_bank', $data);
   }
}