<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Audit Logs</h3>
   </div>
   <div class="card-body">
      <table class="table table-bordered">
         <thead>
            <tr>
               <th>Timestamp</th>
               <th>User</th>
               <th>Activity Type</th>
               <th>Details</th>
               <th>IP Address</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
               <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
               <td><?= $log['username'] ?></td>
               <td><?= ucfirst(str_replace('_', ' ', $log['activity_type'])) ?></td>
               <td>
                  <pre><?= json_encode(json_decode($log['details']), JSON_PRETTY_PRINT) ?></pre>
               </td>
               <td><?= $log['ip_address'] ?></td>
            </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>
<?= $this->endSection() ?>