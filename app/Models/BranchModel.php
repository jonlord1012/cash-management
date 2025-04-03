<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model
{
   protected $table = 'branches';

   protected $useAutoIncrement = false;
   protected $primaryKey = 'branch_code';
   protected $allowedFields = [
      'branch_code',
      'name',
      'is_head_office',
      'is_active',
      'create_user',
      'update_user'
   ];
   protected $validationRules = [
      'name' => 'required|max_length[255]',
      'is_head_office' => 'permit_empty|in_list[0,1]',
      'is_active' => 'permit_empty|in_list[0,1]',
   ];
   protected $useTimestamps = true;
   protected $createdField = 'create_date';
   protected $updatedField = 'update_date';

   public function getAutocompleteData()
   {
      return $this->select('branch_code, name as branch_name')->findAll();
   }

   public function toggleStatus($id, $userLogin)
   {

      $branch = $this->find($id);
      if (!$branch) {
         log_message('error', "Branch ID {$id} not found");
         return false;
      }
      $newStatus = $branch['is_active'] ? 0 : 1;
      log_message('debug', "Changing status for branch ID {$id} from {$branch['is_active']} to {$newStatus}");

      $result = $this->update($id, [
         'is_active' => $newStatus,
         'update_user' => $userLogin
      ]);

      if (!$result) {
         log_message('error', "Failed to update branch ID {$id}");
      }
      return $result;
   }
   public function createBranch($data, $userLogin)
   {
      $data['create_user'] = $userLogin;
      $data['update_user'] = $userLogin;
      return $this->insert($data);
   }

   public function updateBranch($id, $data, $userLogin)
   {
      $data['update_user'] = $userLogin;
      return $this->update($id, $data);
   }
}