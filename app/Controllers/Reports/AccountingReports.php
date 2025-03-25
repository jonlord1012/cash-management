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
   protected $db;
   public function __construct()
   {
      $this->db = \Config\Database::connect();

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
   private function getDaysInMonth($period)
   {
      $date = \DateTime::createFromFormat('F-Y', $period);
      return $date->format('t'); // Returns number of days in month
   }


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
   public function getCashBankReport()
   {

      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);

      // Get branch code from the logged-in user
      #$branchCode = $this->request->getGet('branchCode');

      // Get date range from the request (if provided)
      $bankCode = $this->request->getGet('bank_code');
      $startDate = $this->request->getGet('start_date');
      $endDate = $this->request->getGet('end_date');

      // Fetch summary data
      $cashBankData = $this->model->getCashBankReport($branch_code, $bankCode, $startDate, $endDate);

      // Prepare data for the view
      $data = [
         'title' => 'Cash / Bank Report',
         'summaryData' => $cashBankData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/cash_bank', $data);
   }


   public function getArusKasBreakdown()
   {

      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);


      $period = 'March 2025';
      $daysInMonth = (int) date('t', strtotime($period));
      $cash_flow_data = $this->model->getDailyCashFlowJSON($period);
      // Prepare data for the view
      $data = [
         'title' => 'Laporan Arus Kas (Breakdown)',
         'period' => $period,
         'days_in_month' => $this->getDaysInMonth($period),
         'cash_flow_data' => $cash_flow_data,
         'start_balance' => '0',
         'end_balance' => '0',
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/arus_kas_breakdown', $data);
   }

   public function getKasPenjualanAktiva()
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
         'title' => 'Kas Penjualan Aktiva',
         'summaryData' => $summaryData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/kas_penjualan_aktiva', $data);
   }
}