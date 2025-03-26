<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Edit Report Group </h3>
   </div>
   <div class="card-body">
      <form action="<?= site_url('admin/rptgroups/update/' . $zreport_groups['id']) ?>" method="post">
         <div class="form-group">
            <label>Group Code</label>
            <input type="text" name="group_code" class="form-control" value="<?= $zreport_groups['group_code'] ?>"
               readonly>
         </div>

         <div class="form-group">
            <label>Group Name</label>
            <input type="text" name="group_name" class="form-control"
               value="<?= old('group_name', $zreport_groups['group_name']) ?>" required>
         </div>

         <div class="form-group">
            <label>Account Code</label>
            <input type="text" class="form-control autocomplete-coa" name="account_code"
               placeholder="Start typing Coa or name..." autocomplete="off" required>
            <small class="form-text text-muted">Example: 10001 / Cash </small>
         </div>
         <div class="form-group">
            <label>Account Name</label>
            <input type="text" class="form-control" name="account_name" id="accountName" readonly>
            <small class="form-text text-muted">This value is auto generated</small>
         </div>
         <button type="submit" class="btn btn-primary">Update Report Group</button>
         <a href="<?= site_url('/admin/rptgroups') ?>" class="btn btn-default">Cancel</a>
      </form>
   </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script>
$(function() {

   // COA Autocomplete
   $(document).ready(function() {
      // COA Autocomplete
      $('.autocomplete-coa').autocomplete({
         source: function(request, response) {
            $.ajax({
               url: '<?= site_url('accounting/getcoa') ?>',
               dataType: 'json',
               data: {
                  term: request.term
               },
               success: function(data) {
                  response(data);
               },
               error: function(xhr) {
                  console.error('COA Search Error:', xhr.responseText);
               }
            });
         },
         minLength: 2,
         select: function(event, ui) {
            if (!ui.item) {
               console.error('Invalid selection');
               return false;
            }
            $('#accountName').val(ui.item.account_name);
            $('[name="account_code"]').val(ui.item.account_code).trigger('change');
            return false;
         }

      }).autocomplete('instance')._renderItem = function(ul, item) {
         return $('<li>')
            .append(`<div>${item.account_code} - ${item.account_name}</div>`)
            .appendTo(ul);
      };

      // Source/Bank Autocomplete
      $('.autocomplete-source').autocomplete({
         source: function(request, response) {
            $.ajax({
               url: '<?= site_url('accounting/getbanks') ?>',
               dataType: 'json',
               data: {
                  term: request.term
               },
               success: function(data) {
                  response(data);
               },
               error: function(xhr) {
                  console.error('Bank Search Error:', xhr.responseText);
               }
            });
         },
         minLength: 2,
         select: function(event, ui) {
            console.log('Selected Bank:', ui.item);
            $('#sourceName').val(ui.item.bank_name);
            $('[name="bank_code"]').val(ui.item.bank_code).trigger('change');
            return false;
         }
      }).autocomplete('instance')._renderItem = function(ul, item) {
         console.log('Rendering item:', item); // Moved before return
         return $('<li>')
            .append(`<div>${item.bank_code} - ${item.bank_name}</div>`)
            .appendTo(ul);
      };
   });

   // Form Submission
   $('#transactionForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
         type: "POST",
         url: $(this).attr('action'),
         data: $(this).serialize(),
         dataType: 'json', // Ensure expecting JSON response
         success: function(response) {
            console.log('Server response:', response);
            if (response.status === 'success') {
               window.location.href = response.redirect;
            } else {
               let errorMsg = response.message + '\n';
               if (response.errors) {
                  errorMsg += Object.values(response.errors).join('\n');
               }
               alert(errorMsg);
            }
         },
         error: function(xhr) {
            console.error('AJAX Error:', xhr.responseText);
            alert('Error: ' + xhr.statusText);
         }
      });
   });
});
</script>

<?= $this->endSection() ?>