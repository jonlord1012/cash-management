<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
   protected $table = 'transactions';
   protected $allowedFields = ['branch_id', 'transaction_date', 'description', 'created_by'];

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