<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Cash Management</h3>
      <a href="<?= site_url('admin/branches/create') ?>" class="btn btn-primary float-right">
         <i class="fas fa-plus"></i> New Transaction
      </a>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>
      <div class="card card-warning">
         <div class="card-header">
            <h3 class="card-title"> Input Transaksi </h3>
         </div>
         <div class="card-body">
            <form>
               <div class="row"></div>
            </form>
         </div>

      </div>



      <table class="table table-bordered">
         <thead>
            <tr>
               <th>Branch Code</th>
               <th>Name</th>
               <th>Type</th>
               <th>Status</th>
               <th>Last Update </th>
               <th>Update By</th>

               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($branches as $branch): ?>
            <tr>
               <td><?= $branch['branch_code'] ?></td>
               <td><?= $branch['name'] ?></td>
               <td>
                  <?= $branch['is_head_office'] ?
                        '<span class="badge bg-primary">Head Office</span>' :
                        '<span class="badge bg-secondary">Branch</span>' ?>
               </td>
               <td>
                  <?= $branch['is_active'] ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>' ?>
               </td>
               <td><?= date('d/m/Y H:i', strtotime($branch['update_date'])) ?></td>
               <td><?= getUserNameByName($branch['update_user']) ?></td>
               <td>
                  <a href="<?= site_url('admin/branches/toggle/' . $branch['id']) ?>"
                     class="btn btn-sm btn-<?= $branch['is_active'] ? 'warning' : 'success' ?>">
                     <?= $branch['is_active'] ? 'Deactivate' : 'Activate' ?>
                  </a>
                  <a href="<?= site_url('admin/branches/edit/' . $branch['id']) ?>" class="btn btn-sm btn-primary mr-1">
                     <i class="fas fa-edit"></i>
                  </a>
               </td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>
<?= $this->endSection() ?>