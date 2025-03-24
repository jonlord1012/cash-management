<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ZReportsNameModel;

class ZReports extends BaseController
{
   protected $model;
   protected $groupModel;
   protected $settingModel;
   protected $userLogin;
   protected $branchCode;
   protected $branchName;


   public function __construct()
   {
      $this->model = new ZReportsNameModel();
      $this->groupModel = new \App\Models\ZReportGroupsModel();
      $this->settingModel = new \App\Models\ZReportSettingsModel();
      $auth = service('auth');
      if ($auth->check()) {
         $this->userLogin = $auth->user()['username'];
         $this->branchName = getBranchNameByUserCode($this->userLogin);
         $this->branchCode = getBranchCodeByUserCode($this->userLogin);
      } else {
         // Redirect to login or throw exception
         throw new \RuntimeException('User not authenticated');
         return redirect()->to('/login');
      }
   }

   /* Z_REPORTS  */
   public function reportName()
   {
      $data = [
         'title' => 'Report Management ' . $this->branchName,
         'zreports_name' => $this->model->orderBy('report_name', 'asc')->findAll()
      ];

      return view('admin/zreport_management', $data);
   }

   public function reportNamesave()
   {

      log_message('debug', 'Save method called with data: ' . print_r($this->request->getPost(), true));

      $validation = $this->validate([
         'report_code' => 'required|min_length[5]|max_length[75]|is_unique[z_reports.report_code]',
         'report_name' => 'required|max_length[255]',

      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'report_code' => $this->request->getPost('report_code'),
         'report_name' => $this->request->getPost('report_name'),
         'create_user' =>  $this->userLogin,
         'update_user' =>  $this->userLogin,
         'is_active' => $this->request->getPost('is_active') ?? 0,

      ];

      try {
         $this->model->save($data);
         return redirect()->to('/admin/rptname')->with('success', 'Report added successfully');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
      }
   }

   public function reportNametoggle($id)
   {
      if ($this->model->toggleStatus($id, $this->userLogin)) {
         return redirect()->back()->with('success', 'Report  status updated');
      }
      return redirect()->back()->with('error', 'Failed to update Report status');
   }
   public function reportNameedit($id)
   {
      $zreports_name = $this->model->find($id);
      if (!$zreports_name) {
         return redirect()->to('/admin/rptname')->with('error', 'Report Name not found');
      }

      return view('admin/zreports_name_edit', [
         'zreports_name' => $zreports_name
      ]);
   }
   public function reportNameupdate($id)
   {
      $zreports_name = $this->model->find($id);
      if (!$zreports_name) {
         return redirect()->back()->with('error', 'Report Name not found');
      }

      $data = [
         'report_name' => $this->request->getPost('report_name'),
         'update_user' => $this->userLogin,
      ];

      if ($this->model->update($id, $data, $this->userLogin)) {
         return redirect()->to('/admin/rptname')->with('success', 'Report updated successfully');
      }

      return redirect()->back()->withInput()->with('errors', $this->model->errors());
   }
   /* END Z_REPORTS */



   /* Z_REPORT_GROUPS */
   public function reportGroups()
   {
      $data = [
         'title' => 'Report Groups Management ' . $this->branchName,
         'zreport_groups' => $this->groupModel->orderBy('group_code', 'asc')->findAll()
      ];

      return view('admin/zreport_groups_management', $data);
   }

   public function reportGroupssave()
   {

      log_message('debug', 'Save method called with data: ' . print_r($this->request->getPost(), true));

      $validation = $this->validate([
         'group_code' => 'required|min_length[5]|max_length[75]|is_unique[z_report_groups.group_code]',
         'group_name' => 'required|max_length[255]',

      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'group_code' => $this->request->getPost('group_code'),
         'group_name' => $this->request->getPost('group_name'),
         'create_user' =>  $this->userLogin,
         'update_user' =>  $this->userLogin,
         'is_active' => $this->request->getPost('is_active') ?? 0,

      ];

      try {
         $this->groupModel->save($data);
         return redirect()->to('/admin/rptgroups')->with('success', 'Report Group added successfully');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
      }
   }

   public function reportGroupstoggle($id)
   {
      if ($this->groupModel->toggleStatus($id, $this->userLogin)) {
         return redirect()->back()->with('success', 'Report Group status updated');
      }
      return redirect()->back()->with('error', 'Failed to update Report Group status');
   }
   public function reportGroupsedit($id)
   {
      $zreport_groups = $this->groupModel->find($id);
      if (!$zreport_groups) {
         return redirect()->to('/admin/rptgroups')->with('error', 'Report Name not found');
      }

      return view('admin/zreport_groups_edit', [
         'zreport_groups' => $zreport_groups
      ]);
   }
   public function reportGroupsupdate($id)
   {
      $zreport_groups = $this->groupModel->find($id);
      if (!$zreport_groups) {
         return redirect()->back()->with('error', 'Report Name not found');
      }

      $data = [
         'group_name' => $this->request->getPost('group_name'),
         'update_user' => $this->userLogin,
      ];

      if ($this->groupModel->update($id, $data, $this->userLogin)) {
         return redirect()->to('/admin/rptgroups')->with('success', 'Report updated successfully');
      }

      return redirect()->back()->withInput()->with('errors', $this->groupModel->errors());
   }
   /* END Z_REPORTS */




   /* Z_REPORT_GROUPS */
   public function reportSetting()
   {
      $data = [
         'title' => 'Report Settings Management ' . $this->branchName,
         'zreport_settings' => $this->settingModel->orderBy('report_code', 'asc')->findAll()
      ];

      return view('admin/zreport_settings_management', $data);
   }
   public function reportSettingnew()
   {
      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);
      $coaModel = new \App\Models\CoaModel();
      $coaList = $coaModel->getAutocompleteData();
      $reportNameModel = new \App\Models\ZReportsNameModel();
      $reportNameList = $reportNameModel->getAutocompleteData();
      $reportGroupsModel = new \App\Models\ZReportGroupsModel();
      $reportGroupList = $reportGroupsModel->getAutocompleteData();
      $data = [
         'title' => 'Input Transaction ' . $branch_name,
         'branchName' => $branch_name,
         'allSettings' => $this->settingModel->orderBy('report_code', 'asc')->findAll(),
         'coaList' => $coaList,
         'reportNameList' => $reportNameList,
         'reportGroupList' => $reportGroupList,
      ];

      return view('admin/zreport_settings_management_create', $data);
   }


   public function reportSettingsave()
   {

      log_message('debug', 'Save method called with data: ' . print_r($this->request->getPost(), true));

      $validation = $this->validate([
         'group_code' => 'required|min_length[5]|max_length[75]|is_unique[z_report_groups.group_code]',
         'group_name' => 'required|max_length[255]',

      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $data = [
         'group_code' => $this->request->getPost('group_code'),
         'group_name' => $this->request->getPost('group_name'),
         'create_user' =>  $this->userLogin,
         'update_user' =>  $this->userLogin,
         'is_active' => $this->request->getPost('is_active') ?? 0,

      ];

      try {
         $this->groupModel->save($data);
         return redirect()->to('/admin/rptgroups')->with('success', 'Report Group added successfully');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
      }
   }

   public function reportSettingtoggle($id)
   {
      if ($this->groupModel->toggleStatus($id, $this->userLogin)) {
         return redirect()->back()->with('success', 'Report Group status updated');
      }
      return redirect()->back()->with('error', 'Failed to update Report Group status');
   }
   public function reportSettingedit($id)
   {
      $zreport_groups = $this->groupModel->find($id);
      if (!$zreport_groups) {
         return redirect()->to('/admin/rptgroups')->with('error', 'Report Name not found');
      }

      return view('admin/zreport_groups_edit', [
         'zreport_groups' => $zreport_groups
      ]);
   }
   public function reportSettingupdate($id)
   {
      $zreport_groups = $this->groupModel->find($id);
      if (!$zreport_groups) {
         return redirect()->back()->with('error', 'Report Name not found');
      }

      $data = [
         'group_name' => $this->request->getPost('group_name'),
         'update_user' => $this->userLogin,
      ];

      if ($this->groupModel->update($id, $data, $this->userLogin)) {
         return redirect()->to('/admin/rptgroups')->with('success', 'Report updated successfully');
      }

      return redirect()->back()->withInput()->with('errors', $this->groupModel->errors());
   }
   /* END Z_REPORTS */



   public function getCoa()
   {
      $term = $this->request->getGet('term');
      log_message('debug', 'COA Search Term: ' . $term);
      $coaModel = new \App\Models\CoaModel();

      $results = $coaModel->select('account_code, account_name')
         ->like('account_code', $term)
         ->orLike('account_name', $term)
         ->limit(10)
         ->findAll();
      log_message('debug', 'COA Results: ' . print_r($results, true));

      return $this->response->setJSON(array_map(function ($item) {
         return [
            'account_code' => $item['account_code'],
            'account_name' => $item['account_name'],
            'value' => $item['account_code'],
            'label' => $item['account_code'] . ' - ' . $item['account_name']
         ];
      }, $results));
   }

   public function getReportsName()
   {
      $term = $this->request->getGet('term');
      log_message('debug', 'Report Name Search Term: ' . $term);
      $reportNameModel = new \App\Models\ZReportsNameModel();

      $results = $reportNameModel->select('report_code, report_name')
         ->where('is_active', '1')
         ->like('report_code', $term)
         ->orLike('report_name', $term)
         ->limit(10)
         ->findAll();
      log_message('debug', 'Report Name Results: ' . print_r($results, true));

      return $this->response->setJSON(array_map(function ($item) {
         return [
            'report_code' => $item['report_code'],
            'report_name' => $item['report_name'],
            'value' => $item['report_code'],
            'label' => $item['report_code'] . ' - ' . $item['report_name']
         ];
      }, $results));
   }

   public function getReportGroups()
   {
      $term = $this->request->getGet('term');
      log_message('debug', 'Report Groups Search Term: ' . $term);
      $reportGroupModel = new \App\Models\ZReportGroupsModel();

      $results = $reportGroupModel->select('group_code, group_name')
         ->where('is_active', '1')
         ->like('group_code', $term)
         ->orLike('group_name', $term)
         ->limit(10)
         ->findAll();
      log_message('debug', 'Report Group Results: ' . print_r($results, true));

      return $this->response->setJSON(array_map(function ($item) {
         return [
            'group_code' => $item['report_code'],
            'group_name' => $item['group_name'],
            'value' => $item['group_code'],
            'label' => $item['group_code'] . ' - ' . $item['group_name']
         ];
      }, $results));
   }
}