<?php

namespace App\Models;

class TransactionModel extends BaseModel
{
   protected $table = 'tr_cash_flow';
   protected $useAutoIncrement = false;
   protected $primaryKey = 'ref_no';

   protected $createdField = 'create_date';
   protected $updatedField = 'update_date';
   protected $returnType     = 'array';

   // Register callbacks for audit logging.
   protected $afterInsert = ['auditAfterInsert'];
   protected $afterUpdate = ['auditAfterUpdate'];
   protected $afterDelete = ['auditAfterDelete'];

   protected $allowedFields = [
      'ref_no',
      'branch_code',
      'branch_name',
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
}