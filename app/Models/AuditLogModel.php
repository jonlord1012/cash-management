<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
   protected $table         = 'audit_log';
   protected $primaryKey    = 'id';
   protected $allowedFields = [
      'user_login',
      'ip_address',
      'computer_name',
      'local_pc_time',
      'action_type',
      'affected_table',
      'record_key',
      'changed_data',
      'execution_timestamp'
   ];

   // We won’t use automatic timestamps here because the DB default works fine.
   protected $useTimestamps = false;
}