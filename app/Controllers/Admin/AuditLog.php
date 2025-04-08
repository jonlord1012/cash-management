<?php

namespace App\Controllers\Admin;

use App\Models\AuditLogModel;
use CodeIgniter\Controller;

class AuditLogs extends Controller
{
   public function index()
   {
      // Render the audit log view.
      return view('audit_logs/index');
   }

   // This method serves JSON data for the DataTables AJAX call.
   public function list()
   {
      $auditModel = new AuditLogModel();
      $logs = $auditModel->orderBy('execution_timestamp', 'DESC')->findAll();

      return $this->response->setJSON(['data' => $logs]);
   }
}