<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
   protected $table = 'tr_cash_flow';

   public function getSummaryReport($branchCode, $startDate = null, $endDate = null)
   {
      $builder = $this->db->table($this->table)
         ->select("transaction_date, account_code, account_name, branch_name, bank_name, description, doc_no, debit, credit ")
         ->where("case branch_name  when '' then null else branch_name end   is not null", null)
         ->orderBy('branch_name, bank_name, transaction_date',  'asc');


      if ($startDate && $endDate) {
         $builder->where('transaction_date >=', $startDate)
            ->where('transaction_date <=', $endDate);
      }

      return $builder->get()->getResultArray();
   }

   public function getCashBankReport($branchCode = null, $bankCode = null, $startDate = null, $endDate = null)
   {
      $builder = $this->db->table($this->table)
         ->select('transaction_date, account_code, account_name, SUM(debit) as total_debit, SUM(credit) as total_credit')
         ->where('branch_code', $branchCode)
         ->where('bank_code', $bankCode)
         ->groupBy('account_code, account_name');

      if ($startDate && $endDate) {
         $builder->where('transaction_date >=', $startDate)
            ->where('transaction_date <=', $endDate);
      }
      $builder->orderBy('transaction_date', 'ASC');

      return $builder->get()->getResultArray();
   }

   public function getDailyCashFlowJSON($period, $branch_code)
   {
      $date = \DateTime::createFromFormat('Ym', $period);
      $startDate = $date->format('Y-m-01');
      $endDate = $date->format('Y-m-t');

      try {
         $sql = "CALL GenerateCashFlowJSON4(?, ?, ?)";
         $query = $this->db->query($sql, [
            $startDate,
            'LAKS',
            $branch_code

         ]);

         $result = $query->getRow();

         if (!$result || empty($result->json_result)) {
            log_message('error', 'Empty stored procedure result');
            return [];
         }

         // Add JSON validation
         $jsonData = json_decode($result->json_result, true);

         if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'JSON Error: ' . json_last_error_msg());
            log_message('debug', 'Raw JSON: ' . $result->json_result);
            return [];
         }
         #return $this->formatCashFlowJSON($jsonData, $startDate);
         return $jsonData;
      } catch (\Exception $e) {
         log_message('error', 'SP Error: ' . $e->getMessage());
         return [];
      }
   }

   private function formatCashFlowJSON($jsonData, $startDate)
   {
      $daysInMonth = (int)date('t', strtotime($startDate));
      $formatted = [];

      foreach ($jsonData as $group) {
         // Handle group daily
         $groupDaily = $this->parseDailyField(
            $group['transaction'] ?? '',
            $daysInMonth
         );

         // Process accounts
         $accounts = [];
         foreach ($group['transaction'] ?? [] as $account) {
            $accountDaily = $this->parseDailyField(
               $account['daily'] ?? [],
               $daysInMonth
            );

            $accounts[] = [
               'account_code' => $account['account_code'] ?? '',
               'account_name' => $account['account_name'] ?? '',
               'total' => $this->cleanNumber($account['total'] ?? 0),
               'daily' => $accountDaily
            ];
         }

         $formatted[] = [
            'group_code' => $group['group_code'] ?? '',
            'group_name' => $group['group_name'] ?? '',
            'total' => $this->cleanNumber($group['total'] ?? 0),
            /*'daily' => $groupDaily,*/
            'accounts' => $accounts
         ];
      }

      return $formatted;
   }

   private function parseDailyField($dailyInput, $daysInMonth)
   {
      // Handle both stringified JSON and arrays
      $dailyData = [];

      if (is_string($dailyInput)) {
         //$decoded = json_decode($dailyInput, true) ?? [];
         //$dailyData = is_array($decoded) ? $decoded : [];
         $dailyData = json_decode($dailyInput, true) ?? [];
      } elseif (is_array($dailyInput)) {
         $dailyData = $dailyInput;
      }

      // Normalize to all days
      $normalized = array_fill(1, $daysInMonth, 0.00);
      foreach ($dailyData as $day => $value) {
         $day = (int)$day;
         if ($day >= 1 && $day <= $daysInMonth) {
            $normalized[$day] = $this->cleanNumber($value);
         }
      }

      return $normalized;
   }

   private function cleanNumber($value)
   {
      return (float)str_replace([',', ' ', '"'], '', (string)$value);
   }

   /* per cash bank  report */


   private function getDataGridColumnsforCashBank()
   {
      $columns = [
         0 => 'bank_account.branch_code',
         1 => 'branches.name',
         2 => 'bank_account.bank_code',
         3 => 'bank_account.bank_name',
         4 => 'bank_account.account_code',
         5 => 'bank_account.bank_account_no',
         6 => 'bank_account.bank_account_name',
         7 => 'bank_account.bank_address',
         8 => 'bank_account.is_active',
         9 => 'bank_account.update_date',
         10 => 'bank_account.update_user'
         // 11 => action column (not sortable)
      ];
      return $columns;
   }
   /*
   public function getDataGridforCashBank($start, $length, $search, $order, $branchCode = null, $bankCode = null, $periodeCode = null)
   {
      // Define column mapping (index => database column name)
      $columns = $this->getDataGridColumnsforCashBank();

      #$builder = $this->builder();
      $builder = $this->db->table('vw_grid_cash_bank');

      // Search
      if ($search) {
         $builder->groupStart()
            ->like('branch_code', $search)
            ->orLike('branch_name', $search)
            ->orLike('bank_code', $search)
            ->orLike('bank_name', $search)
            ->orLike('account_code', $search)
            ->orLike('account_name', $search)
            ->orLike('description', $search)
            ->groupEnd();
      }

      // ADD filter by form values
      if (!empty($branchCode)) {
         $builder->where('branch_code', $branchCode);
      }
      if (!empty($bankCode)) {
         $builder->where('bank_code', $bankCode);
      }
      if (!empty($periodeCode)) {
         $builder->where('periode_code', $periodeCode);
      }

      // Order
      if (!empty($order)) {
         foreach ($order as $o) {
            $colIndex = $o['column'];
            $dir = strtoupper($o['dir']) === 'ASC' ? 'ASC' : 'DESC';
            if (isset($columns[$colIndex])) {
               $builder->orderBy($columns[$colIndex], $dir);
            }
         }
      }
      if (empty($order)) {
         $builder->orderBy('transaction_date ASC, branch_code ASC');
      }
      // Limit
      if ($length > 0) {
         $builder->limit($length, $start);
      }
      $results = $builder->get()->getResultArray();

      // Format data for DataTables
      return array_map(function ($row) {
         return [
            'transaction_date' => $row['transaction_date'],
            'branch_code' => $row['branch_code'],
            'branch_name' => ($row['branch_name']),
            'bank_code' => $row['bank_code'],
            'bank_name' => $row['bank_name'],
            'account_code' => $row['account_code'],
            'account_name' => $row['account_name'],
            'description' => $row['description'],
            'doc_no' => $row['doc_no'],
            'begining_balance' => $row['begining_balance'],
            'debit' => $row['debit'],
            'credit' => $row['credit'],
            'ending_balance' => '0',
            'is_posted' => $row['is_posted'],
            'update_date' => $row['update_date'] ?? $row['create_date'],
            'update_user_name' => getUserNameByName($row['update_user'] ?? $row['create_user'])
         ];
      }, $results);
   }
      */

   public function getDataGridforCashBank($start, $length, $search, $order, $branchCode = null, $bankCode = null, $periodeCode = null)
   {
      $columns = $this->getDataGridColumnsforCashBank();
      $builder = $this->db->table('vw_grid_cash_bank');
      $builder->select("*, 
    (SELECT SUM(t2.debit - t2.credit) 
     FROM vw_grid_cash_bank t2 
     WHERE t2.account_code = vw_grid_cash_bank.account_code 
     AND DATE_FORMAT(t2.transaction_date, '%Y-%m') = DATE_FORMAT(vw_grid_cash_bank.transaction_date, '%Y-%m')
     AND t2.transaction_date <= vw_grid_cash_bank.transaction_date) as running_balance");


      if (!empty($branchCode)) {
         $builder->where('branch_code', $branchCode);
      }

      if (!empty($bankCode)) {
         $builder->where('bank_code', $bankCode);
      }

      if (!empty($periodeCode)) {
         $builder->where('periode_code', $periodeCode);
      }

      // Get total record count BEFORE filtering
      $totalRecords = $builder->countAllResults(false);
      $builder->orderBy('account_code, transaction_date', 'ASC');

      // --- Filters ---
      if (!empty($search)) {
         $builder->groupStart()
            ->orLike('branch_code', $search)
            ->orLike('short_name', $search)
            ->orLike('branch_name', $search)
            ->orLike('bank_code', $search)
            ->orLike('bank_name', $search)
            ->orLike('account_code', $search)
            ->orLike('account_name', $search)
            ->orLike('description', $search)
            ->groupEnd();
      }

      // Get filtered count AFTER search + filters
      $filteredRecords = $builder->countAllResults(false);

      // --- Pagination ---
      if ($length > 0) {
         $builder->limit($length, $start);
      }
      // --- Fetch Data ---
      $results = $builder->get()->getResultArray();
      /*
      // --- Format the Result ---
      $data = array_map(function ($row) {
         $updateUser = $row['update_user'] ?? $row['create_user'];
         $updateUserName = !empty($updateUser) ? getUserNameByName($updateUser) : '-';
         $beginningBalance = floatval($row['begining_balance'] ?? 0);
         $debit = floatval($row['debit'] ?? 0);
         $credit = floatval($row['credit'] ?? 0);
         $endingBalance = $beginningBalance + $debit - $credit ?? 0;
         $runningBalance = floatval($row['running_balance']) ??0;

         return [
            'transaction_date' => ($row['transaction_date']),
            'branch_code' => $row['branch_code'],
            'branch_name' => $row['branch_name'],
            'short_name' => $row['short_name'],
            'bank_code' => $row['bank_code'],
            'bank_name' => $row['bank_name'],
            'account_code' => $row['account_code'],
            'account_name' => $row['account_name'],
            'description' => $row['description'],
            'doc_no' => $row['doc_no'],
            'begining_balance' => format_currency($beginningBalance),
            'debit' => format_currency($debit),
            'credit' => format_currency($credit),
            'ending_balance' => format_currency($runningBalance),
            'is_posted' => formatPostedStatus($row['is_posted']),
            'update_date' => formatDateTime($row['update_date'] ?? $row['create_date']),
            'update_user_name' => $updateUserName,
         ];
      }, $results);
      #return ($data);
*/

      // --- Calculate Running Balances ---
      $runningBalances = []; // To track balances per account per month
      $formattedData = [];

      foreach ($results as $row) {
         $accountCode = $row['account_code'];
         $monthYear = date('Y-m', strtotime($row['transaction_date']));
         $key = $accountCode . '_' . $monthYear;

         // Initialize if this account/month combination hasn't been seen yet
         if (!isset($runningBalances[$key])) {
            $runningBalances[$key] = floatval($row['begining_balance'] ?? 0);
         }

         $debit = floatval($row['debit'] ?? 0);
         $credit = floatval($row['credit'] ?? 0);

         // Calculate current row's ending balance
         $runningBalances[$key] += ($debit - $credit);
         $endingBalance = $runningBalances[$key];

         // Format user information
         $updateUser = $row['update_user'] ?? $row['create_user'];
         $updateUserName = !empty($updateUser) ? getUserNameByName($updateUser) : '-';

         $formattedData[] = [
            'month_year' => $monthYear,
            'transaction_date' => $row['transaction_date'],
            'branch_code' => $row['branch_code'],
            'branch_name' => $row['branch_name'],
            'short_name' => $row['short_name'],
            'bank_code' => $row['bank_code'],
            'bank_name' => $row['bank_name'],
            'account_code' => $row['account_code'],
            'account_name' => $row['account_name'],
            'description' => $row['description'],
            'doc_no' => $row['doc_no'],
            'begining_balance' => format_currency(floatval($row['begining_balance'] ?? 0)),
            'debit' => format_currency($debit),
            'credit' => format_currency($credit),
            'ending_balance' => format_currency($endingBalance),
            'is_posted' => formatPostedStatus($row['is_posted']),
            'update_date' => formatDateTime($row['update_date'] ?? $row['create_date']),
            'update_user_name' => $updateUserName,
         ];
      }

      // --- Return the data in DataTables format ---
      $data = [
         'draw' => intval($_POST['draw'] ?? 1),
         'recordsTotal' => $totalRecords,
         'recordsFiltered' => $filteredRecords,
         'data' => $formattedData,
      ];
      return ($data);
   }


   public function countFilteredforCashBank($search)
   {
      $builder = $this->db->table('vw_grid_cash_bank');

      #$builder = $this->builder();

      if ($search) {
         $builder->groupStart()
            ->like('branch_code', $search)
            ->orLike('branches.name', $search)
            ->orLike('bank_code', $search)
            ->orLike('bank_name', $search)
            ->orLike('account_code', $search)
            ->orLike('account_name', $search)
            ->orLike('description', $search)
            ->groupEnd();
      }

      return $builder->countAllResults();
   }


   /* end per cash bank report */

   public function getCashFlowBreakdown($branchCode, $period)
   {
      $data = [
         'operating' => [
            [
               'name' => 'Kas Diterima dari Pelanggan',
               'total' => 16646021681,
               'daily' => [
                  1 => 483532063,
                  2 => 1943782935,
                  3 => 630922725,
                  // ... up to 31 days
                  31 => 285317702
               ],
               'level' => 1
            ],
         ],
         'investing' => [
            [
               'name' => 'Kas Diterima dari Pelanggan',
               'total' => 16646021681,
               'daily' => [
                  1 => 483532063,
                  2 => 1943782935,
                  3 => 630922725,
                  // ... up to 31 days
                  31 => 285317702
               ],
               'level' => 1
            ],
         ],
         'financing' => [
            [
               'name' => 'Kas Diterima dari Pelanggan',
               'total' => 16646021681,
               'daily' => [
                  1 => 483532063,
                  2 => 1943782935,
                  3 => 630922725,
                  // ... up to 31 days
                  31 => 285317702
               ],
               'level' => 1
            ],
         ],
      ];
      return $data;
   }
}