
// Report Group Autocomplete 
$('#coaCode').autocomplete({
   source: function (request, response) {
      $.ajax({
         url: '<?= site_url('accounting/ getcoa') ?>',
         dataType: 'json',
         data: {
         term: request.term
      },
         success: function (data) {
            console.log(data);
            response(data);
         },
         error: function (xhr) {
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
   }).autocomplete('instance')._renderItem = function (ul, item) {
   //console.log('Rendering item:', item); // Moved before return
   return $('<li>')
      .append(`<div>${item.account_code} - ${item.account_name}</div>`)
      .appendTo(ul);
};