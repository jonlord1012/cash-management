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
}