<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Banks Management</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addBankModal">
         <i class="fas fa-plus"></i> Add Bank
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
               <th>Branch Code</th>
               <th>Branch Name</th>
               <th>Bank Code</th>
               <th>Bank Name</th>
               <th>Account No</th>
               <th>Account Name </th>
               <th>Bank Address</th>
               <th>Status</th>
               <th>Updated at</th>
               <th>Updated By</th>

               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($banks as $bank): ?>
            <tr>
               <td><?= $bank['branch_code'] ?></td>
               <td><?= getBranchShortNameByBranchCode($bank['branch_code']) ?></td>
               <td><?= $bank['bank_code'] ?></td>
               <td><?= $bank['bank_name'] ?></td>
               <td><?= $bank['bank_account_no'] ?></td>
               <td><?= $bank['bank_account_name'] ?></td>
               <td><?= $bank['bank_address'] ?></td>
               <td>
                  <?= $bank['is_active'] ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>' ?>
               </td>
               <td><?= date('d/m/Y H:i', strtotime($bank['update_date'])) ?></td>
               <td><?= getUserNameByName($bank['update_user']) ?></td>
               <td>
                  <a href="<?= site_url('admin/banks/toggle/' . $bank['bank_code']) ?>"
                     class="btn btn-sm btn-<?= $bank['is_active'] ? 'warning' : 'success' ?>">
                     <?= $bank['is_active'] ? 'Deactivate' : 'Activate' ?>
                  </a>
                  <a href="<?= site_url('admin/banks/edit/' . $bank['bank_code']) ?>"
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




<?= $this->endSection() ?>