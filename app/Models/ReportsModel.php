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

   public function getCashBankReport($branchCode, $bankCode, $startDate = null, $endDate = null)
   {
      $builder = $this->db->table($this->table)
         ->select('account_code, account_name, SUM(debit) as total_debit, SUM(credit) as total_credit')
         ->where('branch_code', $branchCode)
         ->where('bank_code', $bankCode)
         ->groupBy('account_code, account_name');

      if ($startDate && $endDate) {
         $builder->where('transaction_date >=', $startDate)
            ->where('transaction_date <=', $endDate);
      }

      return $builder->get()->getResultArray();
   }

   public function getDailyCashFlowJSON($period)
   {
      // Convert period to date range
      $date = \DateTime::createFromFormat('F Y', $period);
      $startDate = $date->format('Y-m-01');
      $endDate = $date->format('Y-m-t');

      try {
         // Call stored procedure
         $sql = "CALL GenerateCashFlowJSON(?, ?, ?)";
         $query = $this->db->query($sql, [
            $startDate,
            $endDate,
            'LAKS'  // Your report code
         ]);

         $result = $query->getRow();
         $jsonData = json_decode($result->json_result ?? '[]', true);

         return $this->formatCashFlowJSON($jsonData, $startDate);
      } catch (\Exception $e) {
         log_message('error', 'Cash Flow SP Error: ' . $e->getMessage());
         return [];
      }
   }

   private function formatCashFlowJSON($jsonData, $startDate)
   {
      $daysInMonth = (int) date('t', strtotime($startDate));
      $formatted = [];

      foreach ($jsonData as $group) {
         $groupEntry = [
            'group_name' => $group['group_name'],
            'total' => (float) str_replace(',', '', $group['total']),
            'daily' => array_fill(1, $daysInMonth, 0),
            'accounts' => []
         ];

         foreach ($group['accounts'] as $account) {
            $accountEntry = [
               'account_name' => $account['account_name'],
               'total' => (float) str_replace(',', '', $account['total']),
               'daily' => array_fill(1, $daysInMonth, 0)
            ];

            foreach ($account['daily'] as $day => $amount) {
               $cleanAmount = (float) str_replace(',', '', $amount);
               $accountEntry['daily'][(int)$day] = $cleanAmount;
               $groupEntry['daily'][(int)$day] += $cleanAmount;
            }

            $groupEntry['accounts'][] = $accountEntry;
         }

         $formatted[] = $groupEntry;
      }

      return $formatted;
   }

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