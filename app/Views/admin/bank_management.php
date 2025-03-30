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
      <table class="table table-bordered" id="dataGrid">
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
         </tbody>
      </table>

   </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
   $('#dataGrid').DataTable({
      processing: true,
      serverSide: true,
      /*buttons: ['colvis', 'excel', 'pdf'], // Add explicit button list*/
      ajax: {
         url: '<?= site_url('admin/banks/grid') ?>',
         type: 'GET'
      },
      columns: [{
            data: 'branch_code'
         },
         {
            data: 'name'
         },
         {
            data: 'bank_code'
         },
         {
            data: 'bank_name'
         },
         {
            data: 'bank_account_no'
         },
         {
            data: 'bank_account_name'
         },
         {
            data: 'bank_address'
         },
         {
            data: 'is_active',
            render: function(data, type, row) {
               return data ?
                  '<span class="badge bg-success">Active</span>' :
                  '<span class="badge bg-danger">Inactive</span>';
            }
         },
         {
            data: 'update_date',
            render: function(data) {
               return moment(data).format('DD/MM/YYYY HH:mm');
            }
         },
         {
            data: 'update_user_name'
         },
         {
            data: null,
            orderable: false,
            render: function(data, type, row) {
               return `
                        <a href="${row.toggle_url}" class="btn btn-sm btn-${row.is_active ? 'warning' : 'success'}">
                            ${row.is_active ? 'Deactivate' : 'Activate'}
                        </a>
                        <a href="${row.edit_url}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    `;
            }
         }
      ],
      dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      /*
         buttons: [{
            extend: 'colvis',
            text: 'Columns',
            className: 'btn-default'
         },
         {
            extend: 'excel',
            text: 'Excel',
            className: 'btn-success',
            exportOptions: {
               columns: ':visible'
            }
         },
         {
            extend: 'pdf',
            text: 'PDF',
            className: 'btn-danger',
            exportOptions: {
               columns: ':visible'
            }
         }
      ]
      */
      buttons: [{
            extend: 'colvis',
            text: 'Columns'
         },
         {
            extend: 'excelHtml5',
            text: 'Excel',
            className: 'btn-success',
            action: function(e, dt, node, config) {
               // Get current search/filter values
               const searchValue = dt.search();
               const order = dt.order();

               // Create export URL with parameters
               const exportUrl = new URL('<?= site_url('admin/banks/export/excel') ?>', window
                  .location.href);
               exportUrl.searchParams.set('search', searchValue);
               exportUrl.searchParams.set('order', JSON.stringify(order));

               // Trigger download
               window.location = exportUrl.href;
            }
         },
         {
            extend: 'pdfHtml5',
            text: 'PDF',
            className: 'btn-danger',
            action: function(e, dt, node, config) {
               const searchValue = dt.search();
               const order = dt.order();

               const exportUrl = new URL('<?= site_url('admin/banks/export/pdf') ?>', window.location
                  .href);
               exportUrl.searchParams.set('search', searchValue);
               exportUrl.searchParams.set('order', JSON.stringify(order));

               window.location = exportUrl.href;
            }
         }
      ],
      colReorder: true,
      responsive: true,
      pageLength: 20,
      order: [
         [1, 'ASC']
      ],
      columnDefs: [{
            orderable: false,
            targets: [10]
         } // Disable sorting for action column
      ]
   });
});
</script>
<?= $this->endSection() ?>