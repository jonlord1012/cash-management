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
               <th>Name</th>
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