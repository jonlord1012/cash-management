<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Audit Logs</h3>
   </div>
   <div class="card-body">
      <table id="auditLogTable" class="display" style="width:100%">
         <thead>
            <tr>
               <th>ID</th>
               <th>User Login</th>
               <th>IP Address</th>
               <th>Computer Name</th>
               <th>Local PC Time</th>
               <th>Action Type</th>
               <th>Affected Table</th>
               <th>Record Key</th>
               <th>Changed Data</th>
               <th>Execution Timestamp</th>
            </tr>
         </thead>
         <tbody>
            <!-- DataTables will populate this body via AJAX -->
         </tbody>
      </table>

   </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
   $('#auditLogTable').DataTable({
      "ajax": {
         "url": "<?= site_url('auditlogs/list'); ?>",
         "dataSrc": "data"
      },
      "columns": [{
            "data": "id"
         },
         {
            "data": "user_login"
         },
         {
            "data": "ip_address"
         },
         {
            "data": "computer_name"
         },
         {
            "data": "local_pc_time"
         },
         {
            "data": "action_type"
         },
         {
            "data": "affected_table"
         },
         {
            "data": "record_key"
         },
         {
            "data": "changed_data"
         },
         {
            "data": "execution_timestamp"
         }
      ]
   });
});
</script>
<?= $this->endSection(); ?>