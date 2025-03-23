<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Transaction Summary Report</h3>
   </div>
   <div class="card-body">
      <!-- Date Range Filter Form -->
      <form method="get" action="<?= site_url('accounting/transaction/summaryReport') ?>" class="mb-4">
         <div class="row">
            <div class="col-sm-6">
               <div class="form-group">
                  <label>Cabang</label> <?= $branchName ?>
                  <!-- <input type="text" class="form-control" name="branch_name" value="<?= $branchName ?>" readonly> -->
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-3">
               <label>Start Date</label>
               <input type="date" name="start_date" class="form-control" value="<?= $startDate ?>">
            </div>
            <div class="col-sm-3">
               <label>End Date</label>
               <input type="date" name="end_date" class="form-control" value="<?= $endDate ?>">
            </div>
            <div class="col-sm-2 align-self-end">
               <button type="submit" class="btn btn-primary">Filter</button>
            </div>
         </div>
   </div>
   </form>

   <!-- Summary Table -->
   <table class="table table-bordered">
      <thead>
         <tr>
            <th>Account Code</th>
            <th>Account Name</th>
            <th>Total Debit</th>
            <th>Total Credit</th>
            <th>Net Balance</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($summaryData as $row): ?>
         <tr>
            <td><?= $row['account_code'] ?></td>
            <td><?= $row['account_name'] ?></td>
            <td><?= number_format($row['total_debit'], 2) ?></td>
            <td><?= number_format($row['total_credit'], 2) ?></td>
            <td><?= number_format($row['total_debit'] - $row['total_credit'], 2) ?></td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
</div>
<?= $this->endSection() ?>