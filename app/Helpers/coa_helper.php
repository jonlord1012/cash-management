<?php

if (!function_exists('old_renderCoaTree')) {
   function old_renderCoaTree($structure, $level = 0)
   {
      $html = '<ul>';

      foreach ($structure as $segment => $children) {
         if ($segment === '_data') continue;

         $data = $children['_data'] ?? null;
         $hasChildren = !empty(array_diff_key($children, ['_data' => null]));

         $html .= '<li' . ($hasChildren ? ' class="jstree-closed"' : '') . '>';

         if ($data) {
            $html .= '<span class="account-item" data-id="' . $data['id'] . '">';
            $html .= '<strong>' . $data['segment4'] . '</strong> - ';
            $html .= ' <span class="badge bg-' . getCategoryColor($data['category']) . '">' . $data['category'] . '</span>';
            $html .= '</span>';
         } else {
            $html .= '<span>' . $segment . '</span>';
         }

         if ($hasChildren) {
            $html .= old_renderCoaTree($children, $level + 1);
         }

         $html .= '</li>';
      }

      $html .= '</ul>';
      return $html;
   }
}

if (!function_exists('renderCoaTree')) {
   function renderCoaTree($nodes, $level = 0)
   {
      $html = '<ul class="flex-column">';

      foreach ($nodes as $node) {
         $data = $node['data'];
         $children = $node['children'];
         $isTotal = ($data['category'] === 'Total') || isset($data['_is_total']);

         $html .= '<li class="' . ($isTotal ? 'jstree-total' : 'jstree-' . strtolower($data['category'])) . '"';
         $html .= ' data-category="' . $data['category'] . '">';

         // Content
         $html .= '<div class="account-node  " >';
         $html .= '<span class="account-code  ">' . $data['account_code'] . '</span>';
         $html .= '<span class="account-name  ">' . $data['account_name'] . '</span>';

         /*  if (!$isTotal) {*/
         $html .= '<span class="badge  ' . getCategoryClass($data['category']) . '">';
         $html .= $data['category'];
         $html .= '</span>';
         /*}*/

         $html .= '</div>';

         // Children
         if (!empty($children)) {
            $html .= renderCoaTree($children, $level + 1);
         }

         $html .= '</li>';
      }

      $html .= '</ul>';
      return $html;
   }
}

// Helper function for category styling
if (!function_exists('getCategoryClass')) {
   function getCategoryClass($category)
   {
      $classes = [
         'Header' => 'bg-primary',
         'Detail' => 'bg-success',
         'Total' => 'bg-warning text-dark',
         'Others' => 'bg-secondary'
      ];
      return $classes[$category] ?? 'bg-light text-dark';
   }
}

if (!function_exists('getCOANameByCode')) {
   function getCOANameByCode($coaCode)
   {
      if ($coaCode === NULL) return false;

      $coaModel = new \App\Models\CoaModel();
      $coaName = $coaModel->where('account_code', $coaCode)->first();
      return $coaName ? $coaName['account_name'] : 'N/A';
   }
}
if (!function_exists('getSourceNameByCode')) {
   function getSourceNameByCode($sourceCode)
   {
      if ($sourceCode === NULL) return false;

      $sourceModel = new \App\Models\SourceModel();
      $sourceName = $sourceModel->where('bank_code', $sourceCode)->first();
      return $sourceName ? $sourceName['bank_name'] : 'N/A';
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
if (!function_exists('format_currency')) {
   function format_currency($value)
   {
      // Handle null values immediately
      if ($value === null) {
         return '-';
      }

      $sanitizedValue = $value;

      // String handling for localized formats
      if (is_string($value)) {
         // Remove thousand separators (assuming . as thousands separator)
         $sanitizedValue = str_replace('.', '', $value);

         // Replace decimal comma with period (European format support)
         $sanitizedValue = str_replace(',', '.', $sanitizedValue);

         // Remove currency symbols and other non-numeric characters
         $sanitizedValue = preg_replace('/[^0-9.-]/', '', $sanitizedValue);
      }

      // Validate numeric value after sanitization
      if (!is_numeric($sanitizedValue)) {
         log_message('error', 'Non-numeric value passed to format_currency: ' . print_r($value, true));
         return '-';
      }

      $number = (float)$sanitizedValue;

      // Handle zero values
      if ($number == 0) {
         return '-';
      }

      // Format numbers with Indonesian-style separators
      $formatted = number_format(abs($number), 2, ',', '.');

      // Negative values in parentheses
      return $number < 0 ? "({$formatted})" : $formatted;
   }
}