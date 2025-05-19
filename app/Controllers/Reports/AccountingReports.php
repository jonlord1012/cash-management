<?php

namespace App\Controllers\Reports;

use App\Controllers\BaseController;
use App\Models\BranchModel;
use App\Models\ReportsModel;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Html;

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
      $date = \DateTime::createFromFormat('F Y', $period);
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
      foreach ($cashBankData as $monthly) {
      }

      // Prepare data for the view
      $data = [
         'title' => 'Cash / Bank Report',
         'summaryData' => $cashBankData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/per_cash_bank', $data);
   }
   public function getArusKasBreakdown()
   {
      $period = 'May 2025'; // Should be dynamic in production


      $isExsport = $this->request->getGet('is_export') ?? "false";
      $myTime = $this->request->getGet('local_pc_time') ?? "false";
      $year = $this->request->getGet('year') ?? date('Y');
      $month = $this->request->getGet('month') ?? date('m');
      $period = $year . $month;

      $myTime = substr($myTime, 0, 10);

      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);

      $daysInMonth = (int) date('t', strtotime($period));

      $pattern = '/^\d{6}$/';
      // Add validation
      if (!preg_match($pattern, $period)) {
         return redirect()->back()->with('error', 'Format periode tidak valid');
      }

      $cash_flow_data = $this->model->getDailyCashFlowJSON($period, $branch_code);
      // 1) Build a lookup of group_code ⇒ group data
      $groupMap = [];
      foreach ($cash_flow_data as $g) {
         $groupMap[$g['group_code']] = $g;
      }

      // 2) For each group, update any account with a non‑empty flag…
      foreach ($cash_flow_data as &$group) {
         foreach ($group['account'] as &$account) {
            if (!empty($account['flag'])) {
               // Parse "'LAKS0001','LAKS0002'" → ['LAKS0001','LAKS0002']
               $codes = array_map(
                  fn($c) => trim($c, " '\""),
                  explode(',', $account['flag'])
               );

               $transactions = [];
               $accountTotal = 0;

               // Pull in every transaction from each flagged group
               foreach ($codes as $code) {
                  $sign = 1;
                  if (strpos($code, '-') === 0) {
                     $sign = -1;
                     $code = substr($code, 1);
                  }

                  if (isset($groupMap[$code])) {
                     foreach ($groupMap[$code]['account'] as $subAcc) {
                        foreach ($subAcc['transaction'] as $txn) {
                           $transactions[] = [
                              'date'         => $txn['date'],
                              'total'        => $sign * abs($txn['total']),
                              'account_code' => $subAcc['account_code'],
                              'account_name' => $subAcc['account_name'],
                           ];
                           $accountTotal += $sign * abs($txn['total']);
                        }
                     }
                  }
               }

               // Overwrite the summary account’s transaction & total
               $account['transaction']   = $transactions;
               $account['total']         = $accountTotal;
               $account['account_code']  = "";  // clear code if you like
               $account['account_name']  = "";  // clear name if you like
            }
         }
         unset($account);
      }
      unset($group);

      // 3) (Optional) Recompute each group’s total as the sum of its accounts
      foreach ($cash_flow_data as &$group) {
         $group['total'] = array_sum(array_column($group['account'], 'total'));
      }
      unset($group);


      // Calculate balances (replace with actual logic)
      $start_balance = 0;
      $end_balance = 0;
      // Add dummy balances (replace with real calculations)
      $data = [
         'title' => 'Laporan Arus Kas (Breakdown)',
         'period' => $period,
         'pickedyear' => $year,
         'pickedmonth' => $month,
         'days_in_month' => $daysInMonth,
         'end_balance' => $end_balance,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
         'cash_flow_data' => $cash_flow_data ?: [],
         'start_balance' => array_sum(array_column($cash_flow_data, 'total')) ?? 0,
         'end_balance' => array_sum(array_column($cash_flow_data, 'total')) ?? 0 // Add your closing balance logic
      ];

      #echo '<pre>' . var_export($data, true) . '</pre>';
      #die();
      if ($isExsport !== 'true') {
         return view('reports/arus_kas_breakdown', $data);
      } else {
         // Step 1: Capture the rendered view output as HTML
         $htmlTable = view('exports/arus_kas_breakdown', $data);

         // Step 2: Use PhpSpreadsheet to convert HTML to Excel
         $reader = new Html();
         #$reader->generateHTMLHeader(true);
         $spreadsheet = $reader->loadFromString($htmlTable);

         // Step 3: Write the spreadsheet to an Excel file
         $writer = new Xlsx($spreadsheet);
         $fileName = $myTime . '-ArusKasBreakdown.xlsx';
         $tempFilePath = WRITEPATH . $fileName;

         // Save the file to a temporary location
         $writer->save($tempFilePath);

         // Step 4: Return the file as a download
         return $this->response->download($tempFilePath, null)->setFileName($fileName);
      }
   }


   public function exportArusKasToExcel()
   {
      #$model = new ReportModel();
      $data['cashFlowData'] = $this->model->getDailyCashFlowJSON();

      // Step 1: Capture the rendered view output as HTML
      $htmlTable = view('reports/arus_kas_breakdown', $data);

      // Step 2: Use PhpSpreadsheet to convert HTML to Excel
      $reader = new Html();
      $spreadsheet = $reader->loadFromString($htmlTable);

      // Step 3: Write the spreadsheet to an Excel file
      $writer = new Xlsx($spreadsheet);
      $fileName = 'ArusKasBreakdown.xlsx';
      $tempFilePath = WRITEPATH . $fileName;

      // Save the file to a temporary location
      $writer->save($tempFilePath);

      // Step 4: Return the file as a download
      return $this->response->download($tempFilePath, null)->setFileName($fileName);
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

   public function getAktivaTetap()
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
         'title' => 'Aktiva Tetap',
         'summaryData' => $summaryData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/aktiva_tetap', $data);
   }

   public function getAktivaPembangunan()
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
         'title' => 'Aktiva Pembangunan',
         'summaryData' => $summaryData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/aktiva_pembangunan', $data);
   }

   public function getKasHutangJangkaPanjang()
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
         'title' => 'Kas Hutang Jangka Panjang',
         'summaryData' => $summaryData,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'branchName' => $branch_name,
         'branchCode' => $branch_code,
      ];

      return view('reports/kas_hutang_jangka_panjang', $data);
   }

   public function renderDataGridforCashBank()
   {
      try {
         $request = $this->request->getGet(); // Change to getGet() if using GET
         validateDataTablesRequest($request);

         #$post = $this->request->getPost();
         $draw = empty($request['draw']) ? 1 : ($request['draw']);
         $start = $request['start'];
         $length = $request['length'];
         $search = $request['search']['value'];
         $order = $request['order'];

         $isExport = $request['export'] ?? false;
         $branchCode = $this->request->getGet('branchCode');
         $bankCode = $this->request->getGet('bankCode');
         $periodeCode = $this->request->getGet('periodeCode');

         $start = $isExport ? 0 : $request['start'];
         $length = $isExport ? -1 : $request['length'];

         $data = $this->model->getDataGridforCashBank($start, $length, $search, $order, $branchCode, $bankCode, $periodeCode);


         log_message('info', 'Fetched Data: ' . json_encode($data));
         return $this->response
            ->setContentType('application/json')
            ->setJSON([
               'draw' => $draw ?? 1,
               'recordsTotal' => $this->model->countAll(),
               'recordsFiltered' => $isExport ? $this->model->countAll() : $this->model->countFilteredforCashBank($search),
               'data' => $data
            ]);


         # return $this->response->setJSON(json_encode($data));
      } catch (\Exception $e) {
         log_message('error', 'DataGrid Error: ' . $e->getMessage());
         return $this->response->setStatusCode(500)->setJSON([
            'error' => $e->getMessage()
         ]);
      }
   }
   public function exportExcel()
   {
      return $this->exportData('excel');
   }

   public function exportPdf()
   {
      return $this->exportData('pdf');
   }
   private function exportData($type)
   {
      $search = $this->request->getGet('search');
      $order = json_decode($this->request->getGet('order'), true);

      $model = new BanksModel();
      $data = $model->getExportData($search, $order);

      if ($type === 'excel') {
         return $this->formatingExportExcel($data);
      }

      return $this->formatingExportPdf($data);
   }
}