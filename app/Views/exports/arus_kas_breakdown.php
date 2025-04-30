<!DOCTYPE html>

<head>
   <!-- admin -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/fontawesome-free/css/all.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/dist/css/adminlte.min.css') ?>">

   <link rel="stylesheet" href="<?= base_url('admin/dist/css/jambuluwuk.css') ?>">
   <!-- jquery-ui -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/jquery-ui/jquery-ui.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/plugins/jquery-ui/jquery-ui.theme.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/plugins/jquery-ui/jquery-ui.structure.min.css') ?>">
   <!-- jsTree -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/jstree/themes/default/style.min.css') ?>">

   <!-- datatables -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
   <link rel="stylesheet"
      href="<?= base_url('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
   <link rel="stylesheet"
      href="<?= base_url('admin/plugins/datatables-colreorder/css/colReorder.bootstrap4.min.css') ?>">
   <link rel="stylesheet"
      href="<?= base_url('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

</head>
<div class="card-header">
   <h3 class="card-title">LAPORAN ARUS KAS BREAKDOWN <?= $branchName; ?></h3>
   <h5 class="float-right">Periode: <?= htmlspecialchars($period) ?></h5>
</div>

<div class="card-body">
   <br />
   <br />
   <br />
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

</html>