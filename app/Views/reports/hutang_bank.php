<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Hutang Bank</h3>

   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <table class="table table-bordered">
         <thead>
            <tr>
               <th class="col-md-2">Deskripsi</th>
               <th class="col-md-2">Total</th>
               <th>January</th>
               <th>Februari</th>
               <th>Maret</th>
               <th>April</th>
               <th>Mei</th>
               <th>Juni</th>
               <th>Juli</th>
               <th>Agustus</th>
               <th>September</th>
               <th>Oktober</th>
               <th>November</th>
               <th>Desember</th>

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