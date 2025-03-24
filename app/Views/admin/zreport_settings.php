<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Report Setting Management</h3>
   </div>
   <div class="card-header warning">
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <div class="card card-warning">
         <div class="card-header">
            <h3 class="card-title"> Input Setting Report </h3>
         </div>
         <div class="card-body">
            <form id="transactionForm" action="<?= site_url('admin/rptsettings/save') ?>" method="post">
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="transaction_date" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Cabang</label>
                        <input type="text" class="form-control" name="branch_name" value="<?= $branchName ?>" readonly>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Report</label>
                        <input type="text" class="form-control autocomplete-coa" name="report_code"
                           placeholder="Start typing REPORT CODE  or NAME ..." autocomplete="off" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label>Nama Report</label>
                     <!--<div id="accountName" class="form-control-static"></div>-->
                     <input type="text" class="form-control" name="report_name" id="reportName" readonly>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Group</label>
                        <input type="text" class="form-control autocomplete-coa" name="group_code"
                           placeholder="Start typing GROUP CODE or GROUP NAME..." autocomplete="off" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label>Nama Group</label>
                     <input type="text" class="form-control" name="group_name" id="groupName" readonly>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Akun</label>
                        <input type="text" class="form-control autocomplete-source" name="account_code"
                           placeholder="Start typing COA  or Akun Name..." autocomplete="off" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label>Nama Akun</label>
                     <input type="text" class="form-control" name="account_name" id="account_Name" readonly>
                  </div>
               </div>


               <button type="submit" class="btn btn-primary float-right">
                  <i class="fas fa-plus"></i> Save Transaction
               </button>
            </form>
         </div>
      </div>

      <div class="mt-4">
         <!-- start grid -->
         <table class="table table-bordered" id="transactionsTable">
            <thead>
               <tr>
                  <th>Report Code</th>
                  <th>Group </th>
                  <th>Account</th>
                  <th>is Debit</th>
                  <th>is Active</th>

                  <th colspan="2">Actions</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($allSettings as $t): ?>
               <tr>
                  <td><?= $t['report_code'] ?></td>
                  <td><?= $t['group_code'] ?> - <?= $t['group_name'] ?></td>
                  <td><?= $t['account_code'] ?> - <?= $t['account_name'] ?></td>
                  <td><?= $t['is_debit'] ?></td>
                  <td><?= $t['is_active'] ?> </td>
                  <td>
                     <button class="btn btn-sm btn-warning edit-btn" data-id="<?= $t['id'] ?>">Edit</button>
                  </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div> <!-- end grid -->


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
               url: '<?= site_url('admin/getcoa') ?>',
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

      //  Report Name Autocomplete
      $('.autocomplete-report-name').autocomplete({
         source: function(request, response) {
            $.ajax({
               url: '<?= site_url('admin/getreportsname') ?>',
               dataType: 'json',
               data: {
                  term: request.term
               },
               success: function(data) {
                  response(data);
               },
               error: function(xhr) {
                  console.error('Report Search Error:', xhr.responseText);
               }
            });
         },
         minLength: 2,
         select: function(event, ui) {
            console.log('Selected Report:', ui.item);
            $('#reportName').val(ui.item.report_name);
            $('[name="report_code"]').val(ui.item.report_code).trigger('change');
            return false;
         }
      }).autocomplete('instance')._renderItem = function(ul, item) {
         console.log('Rendering item:', item); // Moved before return
         return $('<li>')
            .append(`<div>${item.report_code} - ${item.report_name}</div>`)
            .appendTo(ul);
      };

      //  Report Group Autocomplete
      $('.autocomplete-report-group').autocomplete({
         source: function(request, response) {
            $.ajax({
               url: '<?= site_url('admin/getgroupsname') ?>',
               dataType: 'json',
               data: {
                  term: request.term
               },
               success: function(data) {
                  response(data);
               },
               error: function(xhr) {
                  console.error('Group Search Error:', xhr.responseText);
               }
            });
         },
         minLength: 2,
         select: function(event, ui) {
            console.log('Selected Report:', ui.item);
            $('#groupName').val(ui.item.group_name);
            $('[name="group_code"]').val(ui.item.group_code).trigger('change');
            return false;
         }
      }).autocomplete('instance')._renderItem = function(ul, item) {
         console.log('Rendering item:', item); // Moved before return
         return $('<li>')
            .append(`<div>${item.group_code} - ${item.group_name}</div>`)
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