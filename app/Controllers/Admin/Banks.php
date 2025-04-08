<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BanksModel;
use App\Models\BranchModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;


class Banks extends BaseController
{
   protected $model;
   protected $userLogin;
   protected $branchCode;
   protected $branchName;

   public function __construct()
   {
      $this->model = new BanksModel();
      helper('user');
      $auth = service('auth');
      if ($auth->check()) {
         $this->userLogin = $auth->user()['username'];
         $this->branchName = getBranchNameByUserCode($this->userLogin);
         $this->branchCode = getBranchCodeByUserCode($this->userLogin);
      } else {
         // Redirect to login or throw exception
         throw new \RuntimeException('User not authenticated');
         return redirect()->to('login');
      }
   }

   public function index()
   {
      $branches = new BranchModel();
      $coas = new \App\Models\CoaModel();
      $data = [
         'title' => 'Banks Management ' . $this->userLogin,
         'banks' => $this->model->orderBy('branch_code asc, bank_code asc')->findAll(),
         'branches' => $branches->getAutocompleteData(),
         'coas' => $coas->getAutocompleteData(),
      ];

      return view('admin/bank_management', $data);
   }
   public function create()
   {
      $data = [
         'title' => 'Add New Bank ',
         'branch_code' => $this->branchCode,
         'branch_name' => $this->branchName,
         'is_head_office' => getIsHeadOffice($this->branchCode),
      ];
      return view('admin/bank_create', $data);
   }

   public function delete($code)
   {
      if ($this->model->deleteBank($code, $this->userLogin)) {
         return redirect()->back()->with('success', 'Bank status updated');
      }
      return redirect()->back()->with('error', 'Failed to update bank status');
   }
   public function save()
   {

      log_message('debug', 'Save method called with data: ' . print_r($this->request->getPost(), true));
      $bankCode  = $this->request->getPost('bank_code');
      $validation = $this->validate([
         'branch_code' => 'required|max_length[75]',
         'bank_code' => "required|min_length[4]|max_length[75]|is_unique[bank_account.bank_code,bank_code,{$bankCode}]",
         'bank_name' => 'required|max_length[255]',
         'account_code' => 'required|max_length[75]',
         'bank_account_no' => 'required|max_length[50]',
         'bank_account_name' => 'required|max_length[255]',
         'bank_address' => 'required|max_length[255]',
      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      $mode = $this->request->getPost('form_mode');
      if ($mode === '' || $mode === null) {
         $mode = 'create';
      }

      $data = [
         'branch_code' => $this->request->getPost('branch_code'),
         'bank_code' => strtoupper($this->request->getPost('bank_code')),
         'bank_name' => strtoupper($this->request->getPost('bank_name')),
         'account_code' => $this->request->getPost('account_code'),
         'bank_account_no' => strtoupper($this->request->getPost('bank_account_no')),
         'bank_account_name' => $this->request->getPost('bank_account_name'),
         'bank_address' => strtoupper($this->request->getPost('bank_address')),
         'is_active' => $this->request->getPost('is_active') ?? 1,
         'update_user' => strtolower($this->userLogin),
      ];

      try {
         if ($mode === 'create') {
            $this->model->createBank($data, $this->userLogin);
         } elseif ($mode === 'edit') {
            $this->model->updateBank($bankCode, $data, $this->userLogin);
         } else {
            return $this->response->setStatusCode(500)->setJSON([
               'status' => 'error',
               'message' => 'Database error: Undefined mode',
            ]);
         }
         return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Bank saved successfully',
            'redirect' => site_url('/admin/banks')
         ]);
      } catch (\Exception $e) {
         return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
         ]);
      }
      /*
      if ($this->model->createBranch($data, $this->userLogin)) {
         return redirect()->to('/admin/banks')->with('success', 'Bank created successfully');
      }

      return redirect()->back()->withInput()->with('error', 'Failed to create branch');
      */
   }

   public function toggle($code)
   {
      if ($this->model->toggleStatus($code, $this->userLogin)) {
         return redirect()->back()->with('success', 'Bank status updated');
      }
      return redirect()->back()->with('error', 'Failed to update bank status');
   }

   public function edit($code)
   {
      $bank = $this->model->find($code);
      if (!$bank) {
         return redirect()->to('/admin/banks')->with('error', 'Bank not found');
      }

      return view('admin/banks_edit', [
         'bank' => $bank
      ]);
   }

   public function update($code)
   {
      $bank = $this->model->find($code);
      if (!$bank) {
         return redirect()->back()->with('error', 'Bank not found');
      }

      $data = [
         'branch_code' => $this->request->getPost('branch_code'),
         'bank_name' => $this->request->getPost('bank_name'),
         'account_code' => $this->request->getPost('account_code'),
         'bank_account_no' => $this->request->getPost('bank_account_no'),
         'bank_account_name' => $this->request->getPost('bank_account_name'),
         'bank_address' => $this->request->getPost('bank_address'),
         'is_active' => $this->request->getPost('is_active') ? 1 : 0
      ];

      if ($this->model->save($code, $data, $this->userLogin)) {
         return redirect()->to('/admin/banks')->with('success', 'Bank updated successfully');
      }

      return redirect()->back()->withInput()->with('errors', $this->model->errors());
   }
   public function renderDataGrid()
   {
      try {
         $request = $this->request->getGet(); // Change to getGet() if using GET
         validateDataTablesRequest($request);
         $isExport = $request['export'] ?? false;


         #$post = $this->request->getPost();
         $draw = $request['draw'];
         $start = $request['start'];
         $length = $request['length'];
         $search = $request['search']['value'];
         $order = $request['order'];

         $model = new BanksModel();

         $start = $isExport ? 0 : $request['start'];
         $length = $isExport ? -1 : $request['length'];

         $data = $model->getDataGrid($start, $length, $search, $order);

         return $this->response
            ->setContentType('application/json')
            ->setJSON([
               'draw' => $draw,
               'recordsTotal' => $model->countAll(),
               'recordsFiltered' => $isExport ? $model->countAll() : $model->countFiltered($search),
               'data' => $data
            ]);
      } catch (\Exception $e) {
         log_message('error', 'DataGrid Error: ' . $e->getMessage());
         return $this->response->setStatusCode(500)->setJSON([
            'error' => $e->getMessage()
         ]);
      }
   }
   public function exportExcel()
   {
      return $this->exportData('excel');
   }

   public function exportPdf()
   {
      return $this->exportData('pdf');
   }
   private function exportData($type)
   {
      $search = $this->request->getGet('search');
      $order = json_decode($this->request->getGet('order'), true);

      $model = new BanksModel();
      $data = $model->getExportData($search, $order);

      if ($type === 'excel') {
         return $this->formatingExportExcel($data);
      }

      return $this->formatingExportPdf($data);
   }
   private function formatingExportExcel($data)
   {
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Add headers
      $headers = [
         'Branch Code',
         'Branch Name',
         'COA Code',
         'Bank Code',
         'Bank Name',
         'Account No',
         'Account Name',
         'Bank Address',
         'Status',
         'Updated At',
         'Updated By'
      ];

      $sheet->fromArray($headers, null, 'A1');

      // Add data
      $rowNum = 2;
      foreach ($data as $row) {
         $sheet->fromArray([
            $row['branch_code'],
            $row['name'],
            $row['bank_code'],
            $row['bank_name'],
            $row['account_code'],
            $row['bank_account_no'],
            $row['bank_account_name'],
            $row['bank_address'],
            $row['is_active'] ? 'Active' : 'Inactive',
            $row['update_date'],
            $row['update_user']
         ], null, "A{$rowNum}");
         $rowNum++;
      }

      // Set filename
      $filename = 'banks_export_' . date('Ymd_His') . '.xlsx';

      // Create writer and output
      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
      $writer->save('php://output');
      exit;
   }

   private function formatingExportPdf($data)
   {
      $pageOrientation = 'L';
      $pdf = new TCPDF($pageOrientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // Set document information
      $pdf->SetCreator('Binjava CashManagement');
      $pdf->SetTitle('Banks Accounts');
      $pdf->SetHeaderData('', 0, 'Banks Report', date('Y-m-d H:i:s'));
      // Optional: Adjust margins if needed
      $pdf->SetMargins(15, 25, 15);
      $pdf->SetHeaderMargin(10);
      $pdf->SetFooterMargin(10);

      // Add a page
      $pdf->AddPage();

      // Create table content
      $html = '<table border="1">
        <thead>
            <tr>
                <th>Branch Code</th>
                <th>Branch Name</th>
                <th>Bank Code</th>
                <th>Bank Name</th>
                <th>COA</th>
                <th>Account No</th>
                <th>Account Name</th>
                <th>Bank Address</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

      foreach ($data as $row) {
         $html .= '<tr>
            <td>' . $row['branch_code'] . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['bank_code'] . '</td>
            <td>' . $row['bank_name'] . '</td>
            <td>' . $row['account_code'] . '</td>
            <td>' . $row['bank_account_no'] . '</td>
            <td>' . $row['bank_account_name'] . '</td>
            <td>' . $row['bank_address'] . '</td>
            <td>' . ($row['is_active'] ? 'Active' : 'Inactive') . '</td>
        </tr>';
      }

      $html .= '</tbody></table>';

      // Output HTML content
      $pdf->writeHTML($html, true, false, true, false, '');

      // Set filename
      $filename = 'banks_export_' . date('Ymd_His') . '.pdf';

      // Output PDF
      $pdf->Output($filename, 'D');
      exit;
   }
}