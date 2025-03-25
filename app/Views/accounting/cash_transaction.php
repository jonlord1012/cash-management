<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Cash Management</h3>
      <a href="<?= site_url('accounting/transaction/new') ?>" class="btn btn-primary float-right">
         <i class="fas fa-plus"></i> New Transaction
      </a>
   </div>
   <div class="card-header warning">
      <h3 class="card-title">Ref No : &nbsp;</h3> <label><?= $refNo; ?> </label>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>


      <div class="mt-4">
         <!-- start grid -->
         <table class="table table-bordered" id="transactionsTable">
            <thead>
               <tr>
                  <th>Ref No</th>
                  <th>Account</th>
                  <th>Source</th>
                  <th>Note</th>
                  <th>Cek/BG</th>

                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Date</th>
                  <th colspan="2">Actions</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($transactions as $t): ?>
               <tr>
                  <td><?= $t['ref_no'] ?></td>
                  <td><?= $t['account_code'] ?> - <?= $t['account_name'] ?></td>
                  <td><?= $t['bank_code'] ?> - <?= $t['bank_name'] ?></td>
                  <td><?= $t['description'] ?></td>
                  <td><?= $t['doc_no'] ?> </td>
                  <td><?= number_format($t['debit']) ?></td>
                  <td><?= number_format($t['credit']) ?></td>
                  <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                  <td>
                     <button class="btn btn-sm btn-warning edit-btn" data-id="<?= $t['ref_no'] ?>">Edit</button>
                  </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div> <!-- end grid -->

   </div>
</div>
<?= $this->endSection() ?>