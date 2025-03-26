<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Report Groups Management</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addzReportGroupModal">
         <i class="fas fa-plus"></i> Add Report Group
      </button>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <table class="table table-bordered">
         <thead>
            <tr>
               <th>Group Code</th>
               <th>Group Name</th>
               <th>Account Code</th>
               <th>Account Name</th>
               <th>Status</th>
               <th>Last Update </th>
               <th>Update By</th>

               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($zreport_groups as $zrpt_group): ?>
            <tr>
               <td><?= $zrpt_group['group_code'] ?></td>
               <td><?= $zrpt_group['group_name'] ?></td>
               <td><?= $zrpt_group['account_code'] ?></td>
               <td><?= $zrpt_group['account_name'] ?></td>
               <td>
                  <?= $zrpt_group['is_active'] ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>' ?>
               </td>
               <td><?= date('d/m/Y H:i', strtotime($zrpt_group['update_date'])) ?></td>
               <td><?= getUserNameByName($zrpt_group['update_user']) ?></td>
               <td>
                  <a href="<?= site_url('admin/rptgroups/toggle/' . $zrpt_group['id']) ?>"
                     class="btn btn-sm btn-<?= $zrpt_group['is_active'] ? 'warning' : 'success' ?>">
                     <?= $zrpt_group['is_active'] ? 'Deactivate' : 'Activate' ?>
                  </a>
                  <a href="<?= site_url('admin/rptgroups/edit/' . $zrpt_group['id']) ?>"
                     class="btn btn-sm btn-primary mr-1">
                     <i class="fas fa-edit"></i>
                  </a>
               </td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>

<!-- Add Report Group Modal -->
<div class="modal fade" id="addzReportGroupModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <form action="<?= site_url('admin/rptgroups/save') ?>" method="post">
            <div class="modal-header">
               <h4 class="modal-title">Add New Report Group</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>Group Code</label>
                  <input type="text" name="group_code" class="form-control" placeholder="XXXX" required>
                  <small class="form-text text-muted">Example: LAKS0001</small>
               </div>

               <div class="form-group">
                  <label>Group Name</label>
                  <input type="text" name="group_name" class="form-control" required>
                  <small class="form-text text-muted">Example: Kas di Terima dari Pelanggan </small>
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

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save Report Group</button>
            </div>
         </form>
      </div>
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