<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityModel extends Model
{
   protected $table = 'activity_logs';
   protected $primaryKey = 'id';
   protected $afterInsert = ['logCreateActivity'];
   protected $afterUpdate = ['logUpdateActivity'];
   #protected $request = service('request'); // Get request service
   protected $allowedFields = [
      'username',
      'activity_type',
      'details',
      'ip_address',
      'user_agent'
   ];
   protected $useTimestamps = true;

   public function logActivity($data)
   {
      return $this->insert([
         'username' => $data['username'],
         'activity_type' => $data['activity_type'],
         'details' => json_encode($data['details']),
         'ip_address' => $data['ip_address'],
         'user_agent' => $data['user_agent']
      ]);
   }

   protected function logCreateActivity(array $data)
   {
      $request = service('request');
      $activityModel = new ActivityModel();
      $activityModel->logActivity([
         'username' => session('username'),
         'activity_type' => 'user_create',
         'details' => [
            'created_user_id' => $data['id'],
            'username' => $data['data']['username']
         ],
         'ip_address' => $request->getIPAddress()
      ]);
      return $data;
   }

   protected function logUpdateActivity(array $data)
   {
      $request = service('request');
      $activityModel = new ActivityModel();
      $activityModel->logActivity([
         'username' => session('username'),
         'activity_type' => 'user_update',
         'details' => [
            'updated_user_id' => $data['id'],
            'changes' => $data['data']
         ],
         'ip_address' => $request->getIPAddress()
      ]);
      return $data;
   }
}