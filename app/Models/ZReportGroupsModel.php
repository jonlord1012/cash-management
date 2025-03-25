<?php

namespace App\Models;

use CodeIgniter\Model;

class ZReportGroupsModel extends Model
{
   protected $table = 'z_report_groups';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'group_code',
      'group_name',
      'is_active',
      'create_user',
      'update_user'
   ];
   protected $validationRules = [
      'group_code' => 'required|max_length[75]',
      'group_name' => 'required|max_length[255]',
      'is_active' => 'permit_empty|in_list[0,1]',
   ];
   protected $useTimestamps = true;
   protected $createdField = 'create_date';
   protected $updatedField = 'update_date';

   public function getAutocompleteData()
   {
      return $this->select('group_code, group_name')->findAll();
   }

   public function toggleStatus($id, $userLogin)
   {

      $report = $this->find($id);
      if (!$report) {
         log_message('error', "Report ID {$id} not found");
         return false;
      }
      $newStatus = $report['is_active'] ? 0 : 1;
      log_message('debug', "Changing status for Report ID {$id} from {$report['is_active']} to {$newStatus}");

      $result = $this->update($id, [
         'is_active' => $newStatus,
         'update_user' => $userLogin
      ]);

      if (!$result) {
         log_message('error', "Failed to update Report ID {$id}");
      }
      return $result;
   }
   public function createZReport($data, $userLogin)
   {
      $data['create_user'] = $userLogin;
      $data['update_user'] = $userLogin;
      return $this->insert($data);
   }

   public function updateZReport($id, $data, $userLogin)
   {
      $data['update_user'] = $userLogin;
      return $this->update($id, $data);
   }
}