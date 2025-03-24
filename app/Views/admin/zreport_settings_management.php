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
                  <input type="text" name="report_code" class="form-control" placeholder="XXXX" required>
                  <small class="form-text text-muted">Type 2 characters to trigger autocomplete</small>
               </div>

               <div class="form-group">
                  <label>Group Name</label>
                  <input type="text" name="group_code" class="form-control" required>
                  <small class="form-text text-muted">Type 2 characters to trigger autocomplete </small>
               </div>

               <div class="form-group">
                  <label>Account Name</label>
                  <input type="text" name="account_code" class="form-control" required>
                  <small class="form-text text-muted">Type 2 characters to trigger autocomplete</small>
               </div>

               <div class="form-group">
                  <label>Is Debit</label>
                  <input type="text" name="is_debit" class="form-control" required>
                  <small class="form-text text-muted">Report position (Debit or Credit) </small>
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