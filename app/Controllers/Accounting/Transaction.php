<?php

namespace App\Controllers\Accounting;

use App\Controllers\BaseController;

use App\Libraries\Auth;
use App\Models\BanksModel;
use App\Models\CoaModel;
use App\Models\BranchModel;
use App\Models\TransactionModel;


class Transaction extends BaseController
{
   protected $model;
   protected $userLogin;
   protected $branchCode;
   protected $branchName;

   public function __construct()
   {
      $this->model = new TransactionModel();
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

   public function index()
   {
      $data = [
         'title' => 'Input Transaction ' . $this->userLogin,
         'transactions' => $this->model->orderBy('transaction_date', 'DESC')->findAll()
      ];

      return view('accounting/view_transaction', $data);
   }

   public function getCoa()
   {
      $term = $this->request->getGet('term');
      log_message('debug', 'COA Search Term: ' . $term);
      $coaModel = new CoaModel();

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

   public function getBanks()
   {
      $term = $this->request->getGet('term');
      $branchModel = new \App\Models\BranchModel();
      $bankModel = new BanksModel();

      $getIsHO = $branchModel->find($this->branchCode);

      if ($getIsHO['is_head_office'] === '0') {
         $builder = $bankModel->select('bank_code, bank_name, account_code')
            ->where('branch_code', $this->branchCode);
      } else {
         $builder = $bankModel->select('bank_code, bank_name, account_code');
      }

      $results = $builder->groupStart()
         ->orLike('bank_code', $term)
         ->orLike('bank_name', $term)
         ->groupEnd()
         ->limit(10)
         ->findAll();

      #$results = $builder->get()->getResultArray();
      #print_r($builder->getLastQuery()->getQuery());
      #die();

      /*$results = $bankModel->select('bank_code, name as bank_name')
         ->where('branch_code', $this->branchCode)
         ->groupStart()
         ->like('bank_code', $term)
         ->orLike('name', $term)
         ->groupEnd()
         ->limit(10)
         ->findAll();
      */
      return $this->response->setJSON(array_map(function ($item) {
         return [
            'bank_code' => $item['bank_code'],
            'bank_name' => $item['bank_name'],
            'account_code' => $item['account_code'],
            'value' => $item['bank_code'],
            'label' => $item['bank_code'] . ' - ' . $item['bank_name'] . ' - ' . $item['account_code']
         ];
      }, $results));
   }

   public function new()
   {
      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);
      $refNo = $this->model->generateRefNo($this->branchCode);
      $coaModel = new CoaModel();
      $coaList = $coaModel->getAutocompleteData();
      $bankModel = new BanksModel();
      $sourceList = $bankModel->getAutocompleteData($this->branchCode);
      $data = [
         'title' => 'Input Transaction ' . $branch_name,
         'refNo' => $refNo,
         'branchName' => $branch_name,
         'transactions' => $this->model->orderBy('transaction_date', 'DESC')->findAll(),
         'coaList' => $coaList,
         'sourceList' => $sourceList,
      ];

      return view('accounting/cash_transaction_create', $data);
   }

   public function authenticate()
   {
      $username = $this->request->getPost('username');
      $password = $this->request->getPost('password');

      if (service('auth')->login($username, $password)) {
         return redirect()->to('/admin/coa');
      }

      return redirect()->back()->withInput()->with('error', 'Invalid credentials');
   }

   public function viewTransaction()
   {
      $branch_name = getBranchNameByUserCode($this->userLogin);
      $branch_code = getBranchCodeByUserCode($this->userLogin);
      $refNo = $this->model->generateRefNo($branch_code);
      $coaModel = new CoaModel();
      $coaList = $coaModel->getAutocompleteData();
      $data = [
         'title' => 'Input Transaction ' . $branch_name,
         'refNo' => $refNo,
         'branchName' => $branch_name,
         'transactions' => $this->model->orderBy('transaction_date', 'DESC')->findAll(),
         'coaList' => $coaList,

      ];
      return view('accounting/cash_transaction', $data);
   }

   public function save()
   {
      try {
         $this->model = new TransactionModel();
         $validation = $this->validate([
            'transaction_date' => 'required|valid_date',
            'account_code' => 'required',
            'bank_code' => 'required',
            'debit' => 'required|numeric',
            'credit' => 'required|numeric'
         ]);

         /*
      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }
      */
         if (!$validation) {
            log_message('error', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            return $this->response->setJSON([
               'status' => 'error',
               'message' => 'Validation failed',
               'errors' => $this->validator->getErrors()
            ]);
         }
         // Get branch code
         $branchCode = getBranchCodeByUserCode($this->userLogin);
         if (!$branchCode) {
            throw new \Exception('Branch code not found');
         }

         $data = [
            'ref_no' => $this->model->generateRefNo($this->branchCode),
            'branch_code' =>  $branchCode,
            'branch_name' =>  getBranchNameByUserCode($this->userLogin),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'account_code' => $this->request->getPost('account_code'),
            'account_name' => $this->request->getPost('account_name'),
            'bank_code' => $this->request->getPost('bank_code'),
            'bank_name' => $this->request->getPost('bank_name'),
            'doc_no' => $this->request->getPost('document_no'),
            'description' => $this->request->getPost('description'),
            'debit' => (float)$this->request->getPost('debit'),
            'credit' => (float)$this->request->getPost('credit'),
            'create_user' => $this->userLogin,
            'create_date' => date('Y-m-d H:i:s')
         ];
         log_message('debug', 'Saving transaction data: ' . print_r($data, true));


         if ($this->model->save($data)) {
            return $this->response->setJSON([
               'status' => 'success',
               'message' => 'Transaction saved successfully',
               'redirect' => site_url('accounting/transaction/new')
            ]);
         }
         log_message('error', 'Failed to save transaction: ' . print_r($this->model->errors(), true));

         return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to save transaction',
            'errors' => $this->model->errors()
         ]);
      } catch (\Exception $e) {
         log_message('error', 'Transaction save error: ' . $e->getMessage());
         return $this->response->setJSON([
            'status' => 'error',
            'message' => $e->getMessage()
         ]);
      }
   }
}