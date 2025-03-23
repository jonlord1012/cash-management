<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Report Management</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addzReportNameModal">
         <i class="fas fa-plus"></i> Add Report Name
      </button>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <table class="table table-bordered">
         <thead>
            <tr>
               <th>Report Code</th>
               <th>Name</th>
               <th>Status</th>
               <th>Last Update </th>
               <th>Update By</th>

               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($zreports_name as $zrpt_name): ?>
            <tr>
               <td><?= $zrpt_name['report_code'] ?></td>
               <td><?= $zrpt_name['report_name'] ?></td>
               <td>
                  <?= $zrpt_name['is_active'] ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>' ?>
               </td>
               <td><?= date('d/m/Y H:i', strtotime($zrpt_name['update_date'])) ?></td>
               <td><?= getUserNameByName($zrpt_name['update_user']) ?></td>
               <td>
                  <a href="<?= site_url('admin/rptname/toggle/' . $zrpt_name['id']) ?>"
                     class="btn btn-sm btn-<?= $zrpt_name['is_active'] ? 'warning' : 'success' ?>">
                     <?= $zrpt_name['is_active'] ? 'Deactivate' : 'Activate' ?>
                  </a>
                  <a href="<?= site_url('admin/rptname/edit/' . $zrpt_name['id']) ?>"
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

<!-- Add COA Modal -->
<div class="modal fade" id="addzReportNameModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <form action="<?= site_url('admin/coa/save') ?>" method="post">
            <div class="modal-header">
               <h4 class="modal-title">Add New Report Name</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>Report Code</label>
                  <input type="text" name="report_code" class="form-control" placeholder="XXXX" required>
                  <small class="form-text text-muted">Example: LAKS</small>
               </div>

               <div class="form-group">
                  <label>Report Name</label>
                  <input type="text" name="report_name" class="form-control" required>
                  <small class="form-text text-muted">Example: Laporan Arus Kas (Summary) </small>
               </div>

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save ReportName</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?= $this->endSection() ?>