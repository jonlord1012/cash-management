<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">LAPORAN ARUS KAS BREAKDOWN</h3>
      <h5 class="float-right">Periode: <?= $period ?></h5>
   </div>

   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-bordered table-striped">
            <thead class="bg-primary">
               <tr>
                  <th style="width: 35%">Keterangan</th>
                  <th class="text-right">Total</th>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <th class="text-right"><?= $day ?></th>
                  <?php endfor; ?>
               </tr>
            </thead>

            <tbody>
               <?php foreach ($cash_flow as $group): ?>
               <tr class="table-info">
                  <td colspan="<?= $days_in_month + 2 ?>">
                     <strong><?= $group['group_name'] ?></strong>
                  </td>
               </tr>

               <!-- Account Details -->
               <?php foreach ($group['accounts'] as $account): ?>
               <tr>
                  <td style="padding-left: 20px">
                     <?= $account['account_name'] ?>
                  </td>
                  <td class="text-right">
                     <?= format_currency($account['total']) ?>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <td class="text-right">
                     <?= format_currency($account['daily'][$day] ?? 0) ?>
                  </td>
                  <?php endfor; ?>
               </tr>
               <?php endforeach; ?>

               <!-- Group Total -->
               <tr class="table-warning">
                  <td><strong>Total <?= $group['group_name'] ?></strong></td>
                  <td class="text-right">
                     <?= format_currency($group['total']) ?>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <td class="text-right">
                     <?= format_currency($group['daily'][$day] ?? 0) ?>
                  </td>
                  <?php endfor; ?>
               </tr>
               <?php endforeach; ?>
            </tbody>

            <!-- Final Totals -->
            <tfoot>
               <tr class="table-danger">
                  <td><strong>Kenaikan (Penurunan Kas)</strong></td>
                  <td class="text-right">
                     <?= format_currency($end_balance - $start_balance) ?>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <td class="text-right">
                     <?= format_currency(0) // Calculate daily net change if needed 
                        ?>
                  </td>
                  <?php endfor; ?>
               </tr>

               <!-- Rest of footer remains the same -->
            </tfoot>
         </table>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
   const table = document.querySelector('.table-responsive');
   table.classList.add('loading');

   setTimeout(() => {
      table.classList.remove('loading');
   }, 500);
});
</script>

<style>
.loading {
   position: relative;
   opacity: 0.6;
   pointer-events: none;
}

.loading::after {
   content: "Memuat data...";
   position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   font-weight: bold;
   color: #007bff;
}
</style>
<?= $this->endSection() ?>