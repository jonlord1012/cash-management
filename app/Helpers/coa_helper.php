<?php

if (!function_exists('renderCoaTree')) {
   function renderCoaTree($structure, $level = 0)
   {
      $html = '<ul>';

      foreach ($structure as $segment => $children) {
         if ($segment === '_data') continue;

         $data = $children['_data'] ?? null;
         $hasChildren = !empty(array_diff_key($children, ['_data' => null]));

         $html .= '<li' . ($hasChildren ? ' class="jstree-closed"' : '') . '>';

         if ($data) {
            $html .= '<span class="account-item" data-id="' . $data['id'] . '">';
            $html .= '<strong>' . $data['segment4'] . '</strong> - ' . $data['name'];
            $html .= ' <span class="badge bg-' . getCategoryColor($data['category']) . '">' . $data['category'] . '</span>';
            $html .= '</span>';
         } else {
            $html .= '<span>' . $segment . '</span>';
         }

         if ($hasChildren) {
            $html .= renderCoaTree($children, $level + 1);
         }

         $html .= '</li>';
      }

      $html .= '</ul>';
      return $html;
   }
}

if (!function_exists('getCategoryColor')) {
   function getCategoryColor($category)
   {
      $colors = [
         'Asset' => 'primary',
         'Liability' => 'danger',
         'Equity' => 'success',
         'Revenue' => 'info',
         'Expense' => 'warning'
      ];
      return $colors[$category] ?? 'secondary';
   }
}