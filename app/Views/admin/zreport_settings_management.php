<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Report Setting Management</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal"
         data-target="#addzReportSettingsModal">
         <i class="fas fa-plus"></i> Add Report Setting
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
               <th>Report Code</th>
               <th>Group Code</th>
               <th>Account Code</th>
               <th>Is Debit</th>
               <th>Status</th>
               <th>Last Update </th>
               <th>Update By</th>

               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($zreport_settings as $zrpt_setting): ?>
            <tr>
               <td><?= $zrpt_setting['report_code'] ?></td>
               <td><?= $zrpt_setting['group_code'] ?></td>
               <td><?= $zrpt_setting['account_code'] ?></td>
               <td><?= $zrpt_setting['is_debit'] ?></td>
               <td>
                  <?= $zrpt_setting['is_active'] ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>' ?>
               </td>
               <td><?= date('d/m/Y H:i', strtotime($zrpt_setting['update_date'])) ?></td>
               <td><?= getUserNameByName($zrpt_setting['update_user']) ?></td>
               <td>
                  <a href="<?= site_url('admin/rptname/toggle/' . $zrpt_setting['id']) ?>"
                     class="btn btn-sm btn-<?= $zrpt_setting['is_active'] ? 'warning' : 'success' ?>">
                     <?= $zrpt_setting['is_active'] ? 'Deactivate' : 'Activate' ?>
                  </a>
                  <a href="<?= site_url('admin/rptname/edit/' . $zrpt_setting['id']) ?>"
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

<!-- Add Reports Name Modal -->
<div class="modal fade" id="addzReportSettingsModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <form action="<?= site_url('admin/rptsettings/save') ?>" method="post">
            <div class="modal-header">
               <h4 class="modal-title">Add New Report Setting</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>Report Name</label>

                  <div class="form-group">
                     <textarea name="report_code" rows="2" class="form-control autocomplete col-sm-4  d-sm-inline-block"
                        autocomplete="off" placeholder="XXXX" id="reportCode"></textarea>
                     <textarea rows="2" class="form-control col-sm-7 d-sm-inline-block" name="report_name"
                        id="reportName" placeholder="Type Report Name" autocomplete="off" readonly></textarea>

                     <small class="form-text text-muted">Type 2 characters to trigger autocomplete</small>
                  </div>
               </div>
               <div class="form-group">
                  <label>Group Name</label>
                  <div class="form-group">
                     <textarea name="report_group_code" rows="2"
                        class="form-control autocomplete col-sm-4  d-sm-inline-block" autocomplete="off"
                        placeholder="XXXX" id="reportGroup" required></textarea>
                     <textarea rows="2" class="form-control col-sm-7 d-sm-inline-block" name="report_group_name"
                        id="reportGroupName" placeholder="Type Report Group" autocomplete="off" readonly></textarea>
                     <small class="form-text text-muted">Type 2 characters to trigger autocomplete</small>
                  </div>
               </div>

               <div class="form-group">
                  <label>Account Name</label>
                  <input type="text" name="account_code" class="form-control" required>
                  <small class="form-text text-muted">Type 2 characters to trigger autocomplete</small>
               </div>

               <div class="form-group">
                  <div class="custom-control custom-checkbox">
                     <input type="checkbox" name="is_debit" value="1" class="custom-control-input" id="is_debit"
                        <?= $zreport_settings['is_debit'] ? 'checked' : '' ?>>
                     <label class="custom-control-label" for="is_debit">
                        Is Debit
                     </label>
                  </div>
                  <small class="form-text text-muted">Position on Report</small>
               </div>


            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save ReportSettings</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<?= \App\Libraries\JsManager::getAutocompleteScript(site_url('reports/getreportcodes'), 'reportCode', 'reportName', 'report_code', 'report_name') ?>
<?= \App\Libraries\JsManager::getAutocompleteScript(
   site_url('reports/getreportgroups'),
   'reportGroup',
   'reportGroupName',
   'group_code',
   'group_name'
) ?>


<!--
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
-->
<?= $this->endSection() ?>