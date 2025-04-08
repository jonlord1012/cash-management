<?php

namespace Config;

use CodeIgniter\Validation\Rules;


class JambulValidation extends Rules
{
   public function exists(?string $value, string $field, array $data): bool
   {
      if (empty($value)) {
         return true;
      }

      // Get the model name from the field if needed (e.g., exists[coa.account_code])
      #$parts = explode('.', $field, 2);
      [$table, $column] = explode('.', $field);
      /* 
      $table = $parts[1] ?? 'coa'; // Default to 'coa' table
      $field = $parts[2];
      */
      $modelClass = 'App\Models\\' . ucfirst($table) . 'Model';
      $model = new $modelClass();

      return $model->where($column, $value)->countAllResults() > 0;
   }
}