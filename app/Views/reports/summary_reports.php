<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Transaction Summary Report</h3>
   </div>
   <div class="card-body">
      <!-- Date Range Filter Form -->
      <form method="get" action="<?= site_url('reports/summary_report') ?>" class="mb-4">
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
            <th>Transaction Date</th>
            <th>Account Code</th>
            <th>Account Name</th>
            <th>Branch Name</th>
            <th>Source</th>
            <th>Note</th>
            <th>Cek/BG</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $balances = [];
         foreach ($summaryData as $row):
            // Create a unique key for each account and branch
            $key = $row['branch_name'] . '-' . $row['bank_name'];

            // Initialize balance if not set
            if (!isset($balances[$key])) {
               $balances[$key] = 0.0;
            }

            // Convert debit and credit to float values (remove commas)
            $debit = (float) str_replace(',', '', $row['debit']);
            $credit = (float) str_replace(',', '', $row['credit']);

            // Calculate new balance
            $newBalance = $balances[$key] + $debit - $credit;
            $balances[$key] = $newBalance;
            // Update the row and balances array
            // $row['balance'] = $newBalance;
            // $balances[$key] = number_format($newBalance, 2, '.', ',');

            // Optional: Format balance with commas for display
            // $row['balance'] = number_format($newBalance, 2, '.', ',');

         ?>
         <tr>
            <td><?= $row['transaction_date'] ?></td>
            <td><?= $row['account_code'] ?></td>
            <td><?= $row['account_name'] ?></td>
            <td><?= $row['branch_name'] ?></td>
            <td><?= $row['bank_name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= $row['doc_no'] ?></td>
            <td><?= number_format($row['debit'], 2, '.', ',') ?></td>
            <td><?= number_format($row['credit'], 2, '.', ',') ?></td>
            <td><?= number_format($newBalance, 2, '.', ',') ?></td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
</div>
<?= $this->endSection() ?>