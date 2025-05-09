<?php

namespace App\Models;

class BanksModel extends BaseModel
{
   protected $table = 'bank_account';

   protected $useAutoIncrement = false;
   protected $primaryKey = 'bank_code';

   protected $useTimestamps = true;
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
      'account_code',
      'bank_account_no',
      'bank_account_name',
      'bank_address',
      'is_active',
      'create_user',
      'update_user',
      'flag01'
   ];
   protected $validationRules = [
      'branch_code' => 'required|max_length[75]',
      'bank_code' => 'required|max_length[75]',
      'bank_name' => 'required|max_length[255]',
      'account_code' => 'required|max_length[75]',
      'bank_account_no' => 'required|max_length[75]',
      'bank_account_name' => 'required|max_length[255]',
      'is_head_office' => 'permit_empty|in_list[0,1]',
      'is_active' => 'permit_empty|in_list[0,1]',
   ];
   public function getAutocompleteData($branch_code)
   {
      $branchModel = new \App\Models\BanksModel;
      $getIsHO = $branchModel->find($branch_code);
      $getIsHO['is_head_office'] ?? '0';
      $builder = $this->db->table('bank_account')
         ->select('bank_code, bank_name, account_code ');
      if ($getIsHO['is_head_oficce'] === '0') {
         $results = $builder->groupStart()
            ->where('branch_code', $branch_code)
            ->groupEnd()
            ->get()->getResultArray();
      }
      return $results;
   }
   public function toggleStatus($code, $userLogin)
   {
      #$bank = $this->select('*')->where('bank_code', $code)->get()->getResult();
      $banks = $this->find($code);
      if (!$banks) {
         log_message('error', "Bank Code {$code} not found");
         return false;
      }
      $newStatus = $banks['is_active'] ? 0 : 1;
      log_message('debug', "Changing status for Bank Code {$code} from {$banks['is_active']} to {$newStatus}");

      $result = $this->update($code, [
         'is_active' => $newStatus,
         'update_user' => $userLogin
      ]);

      if (!$result) {
         log_message('error', "Failed to update branch Code {$code}");
      }
      return $result;
   }
   public function deleteBank($code, $userLogin)
   {
      $banks = $this->find($code);
      if (!$banks) {
         log_message('error', "Bank Code {$code} not found");
         return false;
      }
      log_message('debug', "Deleting Banks {$code}");

      $result = $this->update($code, [
         'flag01' => 'deleted',
         'update_user' => $userLogin
      ]);

      if (!$result) {
         log_message('error', "Failed to update branch Code {$code}");
      }
      return $result;
   }
   public function createBank($data, $userLogin)
   {
      $data['create_user'] = strtolower($userLogin);
      $data['update_user'] = strtolower($userLogin);
      return $this->insert($data);
   }

   public function updateBank($code, $data, $userLogin)
   {
      $data['update_user'] = strtolower($userLogin);
      $this->update($code, $data) or die(print_r($data));
   }

   private function getDataGridColumns()
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

   /* render for  */
   public function getDataGrid($start, $length, $search, $order)
   {
      // Define column mapping (index => database column name)
      $columns = $this->getDataGridColumns();

      #$builder = $this->builder();
      $builder = $this->db->table('bank_account')
         ->select('bank_account.*, branches.name')
         ->join('branches', 'branches.branch_code = bank_account.branch_code', 'left')
         ->where('flag01 is null ');

      // Search
      if ($search) {
         $builder->groupStart()
            ->like('bank_account.branch_code', $search)
            ->orLike('branches.name', $search)
            ->orLike('bank_code', $search)
            ->orLike('bank_name', $search)
            ->orLike('bank_account_name', $search)
            ->orLike('bank_address', $search)
            ->groupEnd();
      }

      // Order
      if (!empty($order)) {
         foreach ($order as $o) {
            $colIndex = $o['column'];
            $dir = strtoupper($o['dir']) === 'ASC' ? 'ASC' : 'DESC';
            if (isset($columns[$colIndex])) {
               $builder->orderBy($columns[$colIndex], $dir);
            }
            /*
            if (array_key_exists($colIndex, $columns)) {
               $builder->orderBy($columns[$colIndex], $dir);
            }
            */
         }
      }
      if (empty($order)) {
         $builder->orderBy('branches.name ASC, bank_account.name');
      }

      // Limit
      if ($length > 0) {
         $builder->limit($length, $start);
      }
      $results = $builder->get()->getResultArray();

      // Format data for DataTables
      return array_map(function ($row) {
         return [
            'branch_code' => $row['branch_code'],
            'name' => getBranchNameByBranchCode($row['branch_code']),
            'bank_code' => $row['bank_code'],
            'bank_name' => $row['bank_name'],
            'account_code' => $row['account_code'],
            'account_name' => getCOANameByCode($row['account_code']),
            'bank_account_no' => $row['bank_account_no'],
            'bank_account_name' => $row['bank_account_name'],
            'bank_address' => $row['bank_address'],
            'is_active' => $row['is_active'],
            'update_date' => $row['update_date'],
            'update_user_name' => getUserNameByName($row['update_user']),
            'toggle_url' => site_url('admin/banks/toggle/' . $row['bank_code']),
            'edit_url' => site_url('admin/banks/edit/' . $row['bank_code']),
            'delete_url' => site_url('admin/banks/delete/' . $row['bank_code']),

         ];
      }, $results);
   }

   public function countFiltered($search)
   {
      $builder = $this->builder();

      if ($search) {
         $builder->groupStart()
            ->like('bank_account.branch_code', $search)
            ->orLike('bank_code', $search)
            ->orLike('bank_name', $search)
            ->groupEnd();
      }

      return $builder->countAllResults();
   }
   public function getExportData($search, $order)
   {
      $builder = $this->builder()
         ->select('bank_account.*, branches.name')
         ->join('branches', 'branches.branch_code = bank_account.branch_code');

      // Apply search
      if ($search) {
         $builder->groupStart()
            ->like('bank_account.branch_code', $search)
            ->orLike('bank_account.bank_code', $search)
            ->orLike('bank_account.bank_name', $search)
            ->groupEnd();
      }

      // Apply sorting
      foreach ($order as $o) {
         $colIndex = $o[0];
         $dir = $o[1];
         $columns = $this->getDataGridColumns(); // Reuse your column mapping
         if (isset($columns[$colIndex])) {
            $builder->orderBy($columns[$colIndex], $dir);
         }
      }

      return $builder->get()->getResultArray();
   }
}