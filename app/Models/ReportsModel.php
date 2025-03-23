<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportsModel extends Model
{
   protected $table = 'tr_cash_flow';

   public function getSummaryReport($branchCode, $startDate = null, $endDate = null)
   {
      $builder = $this->db->table($this->table)
         ->select('account_code, account_name, SUM(debit) as total_debit, SUM(credit) as total_credit')
         ->where('branch_code', $branchCode)
         ->groupBy('account_code, account_name');

      if ($startDate && $endDate) {
         $builder->where('transaction_date >=', $startDate)
            ->where('transaction_date <=', $endDate);
      }

      return $builder->get()->getResultArray();
   }
}