<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityModel;

class AuditLog extends BaseController
{
   protected $model;

   public function index()
   {
      $model = new ActivityModel();
      $data = [
         'title' => 'Audit Logs',
         'logs' => $model->select('activity_logs.*, users.username')
            ->join('users', 'users.username = activity_logs.username')
            ->orderBy('create_date', 'DESC')
            ->findAll()
      ];
      return view('admin/audit_logs', $data);
   }
}