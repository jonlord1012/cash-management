<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;
use App\Models\BranchModel;
use App\Models\ReportsModel;

class AccountingReports extends BaseController
{
   protected $model;
   protected $branches;
   protected $userLogin;
   public function __construct()
   {
      $this->branches = new BranchModel();
      $this->model = new ReportsModel();
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

   /*
   public function summaryInput()
   {
      $data = [
         'title' => 'Branch Management ' . $this->userLogin,
         'branches' => $this->model->orderBy('is_head_office', 'DESC')->findAll()
      ];

      return view('reports/summary_input', $data);
   }
      */

   public function hutangBank()
   {
      $data = [
         'title' => 'Branch Management ' . $this->userLogin,
         'branches' => $this->branches->orderBy('is_head_office', 'DESC')->findAll()
      ];

      return view('reports/hutang_bank', $data);
   }

   public function summaryReport()
   {

      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);

      // Get branch code from the logged-in user
      #$branchCode = $this->request->getGet('branchCode');

      // Get date range from the request (if provided)
      $startDate = $this->request->getGet('start_date');
      $endDate = $this->request->getGet('end_date');

      // Fetch summary data
      $summaryData = $this->model->getSummaryReport($branch_code, $startDate, $endDate);

      // Prepare data for the view
      $data = [
         'title' => 'Transaction Summary Report',
         'summaryData' => $summaryData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/summary_reports', $data);
   }
}