<?php

namespace App\Libraries;

class JsManager
{
   public static function getAutocompleteScript($urlAPI, $codeField, $nameField, $dbFieldCode, $dbFieldName)
   {
      if ((!isset($urlAPI)) || ($urlAPI === '') || ($urlAPI === null)) {
         return 'no API attached';
      }
      if ((!isset($codeField)) || ($codeField === '') || ($codeField === null)) {
         return 'no Input Id for Code Field attached';
      }
      if ((!isset($nameField)) || ($nameField === '') || ($nameField === null)) {
         return 'no Input Id for Name Field attached';
      }
      if ((!isset($dbFieldCode)) || ($dbFieldCode === '') || ($dbFieldCode === null)) {
         return 'no db field name for code attached';
      }
      if ((!isset($dbFieldName)) || ($dbFieldName === '') || ($dbFieldName === null)) {
         return 'no  db field name for name attached';
      }
      return
         <<<SCRIPT
         <script>
            $(document).ready(function() {
            $('#$codeField').autocomplete({
            source: function(request, response) {
            $.ajax({
            url: '$urlAPI',
            dataType: 'json',
            data: {
            term: request.term
            },
            success: function(data) {
            console.log(data);
            response(data);
            },
            error: function(xhr) {
            console.error('$codeField Search Error:', xhr.responseText);
            }
            });
            },
            minLength: 1,
            select: function(event, ui) {
            console.log('Selected:', ui.item);
            $('#$nameField').val(ui.item.$dbFieldName);
            $('[id="$codeField"]').val(ui.item.$dbFieldCode).trigger('change');
            return false;
            }
            }).autocomplete('instance')._renderItem = function(ul, item) {
               const code = item.$dbFieldCode || 'N/A';
               const name = item.$dbFieldName || 'N/A';
            return $('<li>').append('<div>' + code + ' - ' + name + '</div>').appendTo(ul);
               };
               });
         </script>
      SCRIPT;
   }
}