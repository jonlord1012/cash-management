<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CoaModel;

class Coa extends BaseController
{
   protected $model;

   public function __construct()
   {
      $this->model = new CoaModel();
      helper('form');
   }

   public function index()
   {
      helper('coa'); // Add this line

      $data = [
         'title' => 'COA Management',
         'coaStructure' => $this->model->getGroupedCoa(),
         'coaFlatList' => $this->model->getFlatList(),
         'categories' => ['Header', 'Detail', 'Total', 'Other']
      ];

      return view('admin/coa_management', $data);
   }

   public function save()
   {
      $validation = $this->validate([
         'code' => 'required|max_length[75]',
         'name' => 'required|max_length[255]',
         'category' => 'required|in_list[Header,Detail,Total,Other]',
         'header_code' => 'permit_empty|exists[coa.account_code]',
         'total_code' => 'permit_empty|exists[coa.account_code]',
      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }


      $coaData = [
         'account_code' => $this->request->getPost('code'),
         'account_name' => $this->request->getPost('name'),
         'category' => $this->request->getPost('category'),
         'header_code' => $this->request->getPost('header_code'),
         'total_code' => $this->request->getPost('total_code'),
         'is_active' => 1
      ];

      // Clear unnecessary fields based on category
      if ($coaData['category'] === 'Header') {
         $coaData['header_code'] = null; // Clear total_code for Header
      } elseif ($coaData['category'] === 'Total') {
         $coaData['total_code'] = null; // Clear header_code for Total
      }
      try {
         $this->model->save($coaData);

         return $this->response->setJSON([
            'status' => 'success',
            'message' => 'COA saved successfully',
            'redirect' => site_url('/admin/coa')
         ]);
      } catch (\Exception $e) {
         return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
         ]);
      }
      /*
      try {
         $this->model->save($coaData);
         return redirect()->to('/admin/coa')->with('success', 'COA added successfully');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
      }
         */
   }
}