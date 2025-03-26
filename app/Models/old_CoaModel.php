<?php

namespace App\Models;

use CodeIgniter\Model;

class CoaModel extends Model
{
   protected $table = 'coa';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'segment1',
      'segment2',
      'segment3',
      'segment4',
      'account_code',
      'account_name',
      'category',
      'is_active'
   ];

   // Get COA in hierarchical format
   public function getGroupedCoa()
   {
      $coas = $this->orderBy('segment1, segment2, segment3, segment4', 'ASC')
         ->findAll();

      return $this->groupSegments($coas);
   }

   public function getAutocompleteData()
   {
      return $this->select('account_code, account_name')->findAll();
   }
   public function getFlatList()
   {
      $coas = $this->orderBy('account_code', 'ASC')
         ->findAll();

      return $coas;
   }
   // Group COA into hierarchical array
   private function groupSegments($coas)
   {
      $structured = [];

      foreach ($coas as $coa) {
         $path = [
            $coa['segment1'],
            $coa['segment2'],
            $coa['segment3'],
            $coa['segment4']
         ];

         $current = &$structured;
         foreach ($path as $segment) {
            if ($segment === '0000') continue;
            if (!isset($current[$segment])) {
               $current[$segment] = [];
            }
            $current = &$current[$segment];
         }
         $current['_data'] = $coa;
      }

      return $structured;
   }

   // Validate COA format
   public function validateCOA($code)
   {
      $segments = explode('-', $code);

      if (count($segments) !== 4) {
         return 'Invalid format. Use XXXX-XXXX-XXXX-XXXX';
      }

      foreach ($segments as $segment) {
         if (!preg_match('/^\d{4}$/', $segment)) {
            return 'Each segment must be 4 digits';
         }
      }

      return true;
   }
}