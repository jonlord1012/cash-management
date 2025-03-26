<?php

namespace App\Models;

use CodeIgniter\Model;

class CoaModel extends Model
{
   protected $table = 'coa';
   protected $primaryKey = 'id';
   protected $allowedFields = [
      'account_code',
      'account_name',
      'category',
      'header_code',
      'total_code',
      'is_active'
   ];

   // Get COA in hierarchical format
   public function getGroupedCoa()
   {
      $coas = $this->orderBy('account_code', 'ASC')
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
      $map = [];
      $tree = [];

      // Create map and track totals
      foreach ($coas as $coa) {
         $map[$coa['account_code']] = [
            'data' => $coa,
            'children' => [],
            'is_total' => ($coa['category'] === 'Total'),
            'total_code' => $coa['total_code'] ?? null
         ];
      }

      // First pass: Build hierarchy excluding totals
      foreach ($map as $code => &$node) {
         if ($node['is_total']) continue; // Skip totals

         $header_code = $node['data']['header_code'];
         if ($header_code && isset($map[$header_code])) {
            $map[$header_code]['children'][] = &$node;
         } else {
            $tree[] = &$node;
         }
      }

      // Second pass: Add totals to their headers
      foreach ($map as &$node) {
         if ($node['data']['category'] === 'Header') {
            $total_code = $node['data']['total_code'];
            if ($total_code && isset($map[$total_code])) {

               // Remove total from original parent if needed
               $total_parent_code = $map[$total_code]['data']['header_code'];
               if ($total_parent_code && isset($map[$total_parent_code])) {
                  $map[$total_parent_code]['children'] = array_filter(
                     $map[$total_parent_code]['children'],
                     fn($child) => $child['data']['account_code'] !== $total_code
                  );
               }
               // Add total as last child
               $map[$total_code]['data']['_is_total'] = true; // Flag for rendering
               $node['children'][] = $map[$total_code];
            }
         }
      }

      // Sort children: Details first, then others
      array_walk($map, function (&$node) {
         usort($node['children'], function ($a, $b) {
            $order = ['Detail' => 0, 'Total' => 1, 'Header' => 2];
            return $order[$a['data']['category']] <=> $order[$b['data']['category']];
         });
      });
      /*
      echo '<pre>';
      print_r($tree);
      die;
*/
      return $tree;
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