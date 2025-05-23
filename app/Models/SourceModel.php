<?php

namespace App\Models;

use CodeIgniter\Model;

class SourceModel extends Model
{
   protected $table = 'bank_account';
   protected $idKey = 'id';
   protected $primaryKey = 'bank_code';

   protected $useAutoIncrement = false;

   protected $createdField = 'create_date';
   protected $updatedField = 'update_date';
   protected $returnType     = 'array';

   // Register callbacks for audit logging.
   protected $afterInsert = ['auditAfterInsert'];
   protected $afterUpdate = ['auditAfterUpdate'];
   protected $afterDelete = ['auditAfterDelete'];


   protected $allowedFields = [
      'branch_code',
      'bank_code',
      'bank_name',
      'bank_account_no',
      'bank_account_name',
      'bank_address',
      'bank_remarks',
      'flag01',
      'flag02',
      'flag03'
   ];
   public function getAutocompleteData($branch_code)
   {


      return $this->select('bank_code, bank_name')
         ->where('branch_code', $branch_code)
         ->findAll();
   }
}