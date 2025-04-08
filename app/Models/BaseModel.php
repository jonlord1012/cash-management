<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class BaseModel extends Model
{
   protected $auditLogModel;
   protected $request; // Declare a property to hold the request service

   public function __construct()
   {
      parent::__construct();
      $this->auditLogModel = new AuditLogModel();
      $this->request = Services::request();
   }

   /**
    * Dynamically retrieve the record key (primary key value) from callback data.
    */
   protected function getRecordKeyFromCallbackData(array $data)
   {
      $primaryKey = $this->primaryKey;

      // Check if the callback data already includes an "id" index.
      if (isset($data['id'])) {
         return is_array($data['id']) ? json_encode($data['id']) : $data['id'];
      }
      // Otherwise, try reading from the inserted/updated data.
      elseif (isset($data['data'][$primaryKey])) {
         return $data['data'][$primaryKey];
      }
      return null;
   }

   /**
    * A helper that logs the audit data.
    */
   protected function logAudit(string $actionType, array $data): array
   {
      $recordKey = $this->getRecordKeyFromCallbackData($data);
      $auditData = [
         // Assumes you set 'user_login' in session when the user logs in.
         'user_login'     => session()->get('user_login') ?? 'guest',
         // Get the IP address from the server variables.
         'ip_address'     => $this->request->getIPAddress(),
         // Resolve computer name using gethostbyaddr.
         'computer_name'  => isset($_SERVER['REMOTE_ADDR']) ? gethostbyaddr($_SERVER['REMOTE_ADDR']) : $this->request->getIPAddress(),
         // Assuming the client sends local PC time as part of the request (if not, this remains null).
         'local_pc_time'  => $this->request->getVar('local_pc_time') ?? null,
         'action_type'    => $actionType,
         'affected_table' => $this->table,
         'record_key'     => $recordKey,
         // Encode changed data as JSON, if available.
         'changed_data'   => isset($data['data']) ? json_encode($data['data']) : null,
         // execution_timestamp is set automatically using the DB default.
      ];

      // Insert the audit record
      $this->auditLogModel->insert($auditData);
      return $data;
   }

   // Callback for after an insert operation.
   protected function auditAfterInsert(array $data): array
   {
      return $this->logAudit('insert', $data);
   }

   // Callback for after an update operation.
   protected function auditAfterUpdate(array $data): array
   {
      return $this->logAudit('update', $data);
   }

   // Callback for after a delete operation.
   protected function auditAfterDelete(array $data): array
   {
      return $this->logAudit('delete', $data);
   }
}