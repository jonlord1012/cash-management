<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">LAPORAN ARUS KAS BREAKDOWN <?= $branchName; ?></h3>
      <h5 class="float-right">Periode: <?= htmlspecialchars($period) ?></h5>
   </div>
   <div class="card-header">
      <div class="row">
         <?php
         $months =  [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
         ];
         $years = range(date('Y') - 5, date('Y') + 1);
         $month = date('m');
         $year = date('Y');
         ?>
         <div class="col-sm-9 d-flex float-sm-left ">
            <form method="get" class="form-inline " action="<?= site_url('/reports/arus_kas_breakdown'); ?>">
               <div class="d-flex mr-3">
                  <div class="d-flex">
                     <select name="month" class="form-control">
                        <?php foreach ($months as $key => $name): ?>
                        <option value="<?= $key ?>" <?= $key == $pickedmonth ? 'selected' : '' ?>>
                           <?= $name ?>
                        </option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
               <div class="d-flex mr-3 ">
                  <select name="year" class="form-control">
                     <?php foreach ($years as $y): ?>
                     <option value="<?= $y ?>" <?= $y == $pickedyear ? 'selected' : '' ?>>
                        <?= $y ?>
                     </option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="d-flex ">
                  <button type="submit" class="btn btn-success btn-block "><i class="fa fa-passport">&nbsp;</i> <strong>
                        Generate Report</strong></button>
               </div>
            </form>
         </div>
         <div class="col-sm-3 float-right">
            <form class="form-inline float-right " action="<?php echo site_url('/reports/export_arus_kas/'); ?>"
               method="get">
               <input type="hidden" name="is_export" value="true" />
               <button class="btn btn-warning" type="submit">Export Excel</button>
            </form>
         </div>
      </div>
   </div>


   <div class="card-body">
      <?php if (empty($cash_flow_data)) : ?>
      <div class="alert alert-danger">
         <h4>Data Tidak Ditemukan!</h4>
         <p class="mb-0">Gagal memuat data untuk periode <?= htmlspecialchars($period) ?>.</p>
         <?php if (ENVIRONMENT !== 'production') : ?>
         <hr>
         <small class="text-muted">Technical Details:</small>
         <pre><?= print_r($cash_flow_data, true) ?></pre>
         <?php endif; ?>
      </div>
      <?php else : ?>
      <?php
         #echo '<pre>' . var_export($cash_flow_data, true) . '</pre>';
         #die();
         ?>

      <div class="table-responsive">
         <table class="table table-bordered table-striped">
            <thead class="bg-primary">
               <tr>
                  <th style="width: 35%">Keterangan</th>
                  <th class="text-right">Total</th>
                  <?php for ($day = 1; $day <= $days_in_month; $day++) : ?>
                  <th class="text-right"><?= $day ?></th>
                  <?php endfor; ?>
               </tr>
            </thead>

            <tbody>
               <?php foreach ($cash_flow_data as $group): ?>

               <?php
                     $group_per_account[$group['group_code']] = array_fill(1, $days_in_month, 0);
                     $group_daily_total = array_fill(1, $days_in_month, 0); // initialize daily totals
                     foreach ($group['account'] as $account) {
                        $account['flag'] ?? $printMe = true;
                        foreach ($account['transaction'] as $transaction) {
                           $day = (int)$transaction['date'];
                           $account_code  = $transaction['account_code'];

                           if ($day >= 1 && $day <= $days_in_month) {
                              $group_daily_total[$account_code][$day] = $transaction['total'];
                              $group_per_account[$group['group_code']][$day] += $transaction['total'];
                           }
                        }
                     }
                     $account = $group['account'];
                     ?>

               <!-- Group Header -->
               <?php if (empty($account[0]['flag'])) { ?>
               <tr class="table-success">
                  <td colspan="<?= $days_in_month + 2 ?>">
                     <strong><?= htmlspecialchars($group['group_name']) ?></strong>
                  </td>
               </tr>
               <?php } else { ?>
               <tr class="table-danger">
                  <td>
                     <strong><?= htmlspecialchars($group['group_name']) ?></strong>
                  </td>
                  <td class="text-right">
                     <strong><u><?= format_currency($group['total']) ?></u></strong>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <td class="text-right">
                     <strong><u><?= format_currency($group_per_account[$group['group_code']][$day] ?? 0) ?></u></strong>
                  </td>
                  <?php endfor; ?>
               </tr>
               <?php } ?>

               <!-- Account Rows -->
               <?php foreach ($group['account'] as $account): ?>
               <?php if (empty($account['flag'])) { ?>
               <tr>
                  <td style="padding-left: 20px">
                     <?= htmlspecialchars($account['account_name']) ?>
                  </td>
                  <td class="text-right">
                     <?= format_currency($account['total']) ?>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <td class="text-right">
                     <?= format_currency($group_daily_total[$account['account_code']][$day] ?? 0) ?>
                  </td>
                  <?php endfor; ?>
               </tr>
               <?php }  ?>
               <?php endforeach; ?>

               <!-- Group Total Row -->
               <?php if (empty($account['flag'])) { ?>
               <tr class="table-warning">
                  <td><strong>Total <?= htmlspecialchars($group['group_name']) ?></strong></td>
                  <td class="text-right">
                     <i><?= format_currency($group['total']) ?></i>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                  <td class="text-right">
                     <i><?= format_currency($group_per_account[$group['group_code']][$day] ?? 0) ?></i>
                  </td>
                  <?php endfor; ?>
               </tr>
               <?php } ?>
               <?php endforeach; ?>
            </tbody>

            <!-- Final Totals -->
            <tfoot>
               <tr class="table-danger">
                  <td><strong>Kenaikan (Penurunan Kas)</strong></td>
                  <td class="text-right">
                     <?= format_currency(abs($end_balance) - abs($start_balance)) ?>
                  </td>
                  <?php for ($day = 1; $day <= $days_in_month; $day++) : ?>
                  <td class="text-right">
                     <?= format_currency(0) ?>
                  </td>
                  <?php endfor; ?>
               </tr>
            </tfoot>
         </table>
      </div>
      <?php endif; ?>
   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script'); ?>
$(document).ready(function() {
$('#arusKasBreakdownFilter').on('submit', function(e) {
e.preventDefault(); // stop actual submit
if (!$('#month').val() && !$('#year').val() ) {
alert('Please specify filter (Year and Month)');
return;
}
$('#dataGridCashBank').DataTable().ajax.reload(); // reload datatable with new filters
});
});

<?= $this->endSection(); ?>