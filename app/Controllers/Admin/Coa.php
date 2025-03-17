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
         'categories' => ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense']
      ];

      return view('admin/coa_management', $data);
   }

   public function save()
   {
      $validation = $this->validate([
         'code' => 'required|max_length[19]',
         'name' => 'required|max_length[255]',
         'category' => 'required|in_list[Asset,Liability,Equity,Revenue,Expense]'
      ]);

      if (!$validation) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Validate COA format
      $code = $this->request->getPost('code');
      $validationResult = $this->model->validateCOA($code);

      if ($validationResult !== true) {
         return redirect()->back()->withInput()->with('error', $validationResult);
      }

      // Split segments
      $segments = explode('-', $code);

      $coaData = [
         'segment1' => $segments[0],
         'segment2' => $segments[1],
         'segment3' => $segments[2],
         'segment4' => $segments[3],
         'name' => $this->request->getPost('name'),
         'category' => $this->request->getPost('category'),
         'is_active' => 1
      ];

      try {
         $this->model->save($coaData);
         return redirect()->to('/admin/coa')->with('success', 'COA added successfully');
      } catch (\Exception $e) {
         return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
      }
   }
}