<script>
$(document).ready(function() {
   // Report Code/Name Autocomplete
   $('#reportCode').autocomplete({
      source: function(request, response) {
         $.ajax({
            url: '<?= site_url('reports/getreportcodes') ?>',
            dataType: 'json',
            data: {
               term: request.term
            },
            success: function(data) {
               console.log(data);
               response(data);
            },
            error: function(xhr) {
               console.error('Report Codes Search Error:', xhr.responseText);
            }
         });
      },
      minLength: 1,
      select: function(event, ui) {
         console.log('Selected Report Code:', ui.item);
         $('#reportName').val(ui.item.report_name);
         $('[name="report_code"]').val(ui.item.report_code).trigger('change');
         return false;
      }
   }).autocomplete('instance')._renderItem = function(ul, item) {
      //console.log('Rendering item:', item); // Moved before return
      return $('<li>')
         .append(`<div>${item.report_code} - ${item.report_name}</div>`)
         .appendTo(ul);
   };

   // Report Group Autocomplete 
   $('#reportGroup').autocomplete({
      source: function(request, response) {
         $.ajax({
            url: '<?= site_url('reports/getreportgroups') ?>',
            dataType: 'json',
            data: {
               term: request.term
            },
            success: function(data) {
               console.log(data);
               response(data);
            },
            error: function(xhr) {
               console.error('Report Group Search Error:', xhr.responseText);
            }
         });
      },
      minLength: 2,
      select: function(event, ui) {
         console.log('Selected Report Group:', ui.item);
         $('#reportGroupName').val(ui.item.group_name);
         $('[name="report_group_code"]').val(ui.item.group_code).trigger('change');
         return false;
      }
   }).autocomplete('instance')._renderItem = function(ul, item) {
      //console.log('Rendering item:', item); // Moved before return
      return $('<li>')
         .append(`<div>${item.group_code} - ${item.group_name}</div>`)
         .appendTo(ul);
   };

   // Report Group Autocomplete 
   $('#coaCode').autocomplete({
      source: function(request, response) {
         $.ajax({
            url: '<?= site_url('accounting/getcoa') ?>',
            dataType: 'json',
            data: {
               term: request.term
            },
            success: function(data) {
               console.log(data);
               response(data);
            },
            error: function(xhr) {
               console.error('COA Search Error:', xhr.responseText);
            }
         });
      },
      minLength: 2,
      select: function(event, ui) {
         console.log('Selected COA:', ui.item);
         $('#coaCode').val(ui.item.account_name);
         $('[name="coaCode"]').val(ui.item.account_code).trigger('change');
         return false;
      }
   }).autocomplete('instance')._renderItem = function(ul, item) {
      //console.log('Rendering item:', item); // Moved before return
      return $('<li>')
         .append(`<div>${item.account_code} - ${item.account_name}</div>`)
         .appendTo(ul);
   };
});
</script>