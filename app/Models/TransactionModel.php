<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
   protected $table = 'tr_cash_flow';
   protected $useAutoIncrement = false;
   protected $primaryKey = 'ref_no';
   protected $allowedFields = [
      'ref_no',
      'branch_code',
      'transaction_date',
      'account_code',
      'account_name',
      'bank_code',
      'bank_name',
      'doc_no',
      'description',
      'debit',
      'credit',
      'create_user',
      'create_date',
      /*
      'update_user',
      'update_date',
      'is_posted',
      'remarks',
      'flag01',
      'flag02',
      'flag03'
      */
   ];

   public function getCurrentDate($date, $branch_code)
   {
      $data = $this->db->get->where("transaction_date = '" . $date . "' and branch_code='" . $branch_code . "'");
      return $data;
   }
   public function generateRefNo($branch_code)
   {
      if ($branch_code === NULL) return false;
      $currentMonth = date('m'); // Get the current month in numeric format
      $currentYear = date('Y');  // Get the current year

      $lastRefNo =  $this->select('count(ref_no) as counter')
         ->where('branch_code', $branch_code)
         ->where("MONTH(transaction_date)", $currentMonth)
         ->where("YEAR(transaction_date)", $currentYear)
         ->get()
         ->getRow();
      $counter = $lastRefNo->counter + 1;
      $formattedCounter = str_pad($counter, 4, '0', STR_PAD_LEFT);

      $refNo = $branch_code . $currentYear . $currentMonth . $formattedCounter;
      return $refNo;
   }

   public function saveWithEntries($transactionData, $entries)
   {
      $this->db->transStart();

      // Save transaction
      $transactionId = $this->insert($transactionData);

      // Save ledger entries
      $ledgerModel = new LedgerEntryModel();
      foreach ($entries as $entry) {
         $ledgerModel->insert([
            'transaction_id' => $transactionId,
            'coa_id' => $entry['coa_id'],
            'amount' => $entry['amount'],
            'type' => $entry['type']
         ]);
      }

      $this->db->transComplete();
      return $this->db->transStatus();
   }
}