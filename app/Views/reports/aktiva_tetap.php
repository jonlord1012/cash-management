<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- FILTERING -->
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Aktiva Tetap</h3>
   </div>

   <div class="card-body">
   	<form id="transactionForm" action="<?= site_url('accounting/transaction/save') ?>" method="post">
      	<div class="row">
            <div class="col-sm-6">
            	<div class="form-group">
                  <label>Date</label>
                  <input type="date" class="form-control" name="transaction_date" required>
               </div>
            </div>
            <div class="col-sm-6">
            	<div class="form-group">
                  <label>Cabang</label>
                  <input type="text" class="form-control" name="branch_name" value="<?= $branchName ?>" readonly>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-6">
               <div class="form-group">
                  <label>Akun</label>
                  <!-- <input type="text" class="form-control autocomplete-coa" name="account_code" required> -->
                  <input type="text" class="form-control autocomplete-coa" name="account_code" placeholder="Start typing Coa or name..." autocomplete="off" required>
               </div>
            </div>
            <div class="col-sm-6">
               <label>Nama Akun</label>
               <!--<div id="accountName" class="form-control-static"></div>-->
                     <input type="text" class="form-control" name="account_name" id="accountName" readonly>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-6">
            	<div class="form-group">
                  <label>Source</label>
                  <!-- <input type="text" class="form-control autocomplete-source" name="bank_code" required> -->
                  <input type="text" class="form-control autocomplete-source" name="bank_code" placeholder="Start typing bank code or name..." autocomplete="off" required>
               </div>
            </div>
         	<div class="col-sm-6">
               <label>Nama Source</label>
               <!--<div id="sourceName" class="form-control-static"></div>-->
               <input type="text" class="form-control" name="bank_name" id="sourceName" readonly>
            </div>
         </div>
      </form>          
    </div>
</div>
<!-- END FILTERING -->
<!-- =================== -->
<!-- TOTAL ASSET -->
<div class="row px-2">
   <div class="col-md-6 col-lg-6">
        <div class="card bg-primary">
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <h6 class="text-white-50 font-weight-light">TOTAL RINCIAN CASH FLOW</h6>
                    <h3 class="font-weight-bold">Rp. 1,005,882,733</h3>
                </div>
                <div>
                    <i class="fas fa-coins text-lg text-white-50"></i>
                </div>
            </div>
        </div>
   </div>
   <div class="col-md-6 col-lg-6">
   <div class="card">
            <div class="d-flex justify-content-between align-items-center p-3">
                <div>
                    <h6 class="text-black-50 font-weight-light">KAS DIBAYAR UNTUK PEMBELIAN AKTIVA TETAP</h6>
                    <h3 class="font-weight-bold">Rp. 1,005,882,733</h3>
                </div>
                <div>
                    <i class="fas fa-coins text-lg text-black-50"></i>
                </div>
            </div>
        </div>
   </div>
</div>
<!-- END TOTAL ASSET -->
<!-- =================== -->
<!-- DETAILED -->
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Rincian</h3>
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
               <th>Tanggal</th>
               <th>Cabang</th>
               <th>Reference</th>
               <th>Note</th>
               <th>Total</th>
               <th>Source</th>
            </tr>
         </thead>
         <tbody>
         </tbody>
         <tfoot>
            <tr>
               <th colspan="5" class="text-right">Total Bulanan :</th>
               <th id="totalMonthly">791,324,968.00</th>
            </tr>
         </tfoot>
      </table>
   </div>
</div>
<!-- END DETAILED -->
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$(function() {

   // COA Autocomplete
   $(document).ready(function() {
      // COA Autocomplete
      $('.autocomplete-coa').autocomplete({
         source: function(request, response) {
            $.ajax({
               url: '<?= site_url('accounting/getcoa') ?>',
               dataType: 'json',
               data: {
                  term: request.term
               },
               success: function(data) {
                  response(data);
               },
               error: function(xhr) {
                  console.error('COA Search Error:', xhr.responseText);
               }
            });
         },
         minLength: 2,
         select: function(event, ui) {
            if (!ui.item) {
               console.error('Invalid selection');
               return false;
            }
            $('#accountName').val(ui.item.account_name);
            $('[name="account_code"]').val(ui.item.account_code).trigger('change');
            return false;
         }

      }).autocomplete('instance')._renderItem = function(ul, item) {
         return $('<li>')
            .append(`<div>${item.account_code} - ${item.account_name}</div>`)
            .appendTo(ul);
      };

      // Source/Bank Autocomplete
      $('.autocomplete-source').autocomplete({
         source: function(request, response) {
            $.ajax({
               url: '<?= site_url('accounting/getbanks') ?>',
               dataType: 'json',
               data: {
                  term: request.term
               },
               success: function(data) {
                  response(data);
               },
               error: function(xhr) {
                  console.error('Bank Search Error:', xhr.responseText);
               }
            });
         },
         minLength: 2,
         select: function(event, ui) {
            console.log('Selected Bank:', ui.item);
            $('#sourceName').val(ui.item.bank_name);
            $('[name="bank_code"]').val(ui.item.bank_code).trigger('change');
            return false;
         }
      }).autocomplete('instance')._renderItem = function(ul, item) {
         console.log('Rendering item:', item); // Moved before return
         return $('<li>')
            .append(`<div>${item.bank_code} - ${item.bank_name}</div>`)
            .appendTo(ul);
      };
   });

   // Form Submission
   $('#transactionForm').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
         type: "POST",
         url: $(this).attr('action'),
         data: $(this).serialize(),
         dataType: 'json', // Ensure expecting JSON response
         success: function(response) {
            console.log('Server response:', response);
            if (response.status === 'success') {
               window.location.href = response.redirect;
            } else {
               let errorMsg = response.message + '\n';
               if (response.errors) {
                  errorMsg += Object.values(response.errors).join('\n');
               }
               alert(errorMsg);
            }
         },
         error: function(xhr) {
            console.error('AJAX Error:', xhr.responseText);
            alert('Error: ' + xhr.statusText);
         }
      });
   });

   // Table Detail
   // $('#dataGrid').DataTable({
   //    processing: true,
   //    serverSide: true,
   //    /*buttons: ['colvis', 'excel', 'pdf'], // Add explicit button list*/
   //    ajax: {
   //       url: '<?= site_url('admin/banks/grid') ?>',
   //       type: 'GET'
   //    },
   //    columns: [{
   //          data: 'branch_code'
   //       },
   //       {
   //          data: 'name'
   //       },
   //       {
   //          data: 'bank_code'
   //       },
   //       {
   //          data: 'bank_name'
   //       },
   //       {

   //          data: null,
   //          render: function(data, type, row) {
   //             return data.account_code + " -  " + data.account_name;
   //          }

   //       },
   //       {
   //          data: 'bank_account_no'
   //       },
   //       {
   //          data: 'bank_account_name'
   //       },
   //       {
   //          data: 'bank_address'
   //       },
   //       {
   //          data: 'is_active',
   //          render: function(data, type, row) {
   //             return data === '1' ?
   //                '<span class="badge btn-block bg-success">Active</span>' :
   //                '<span class="badge btn-block bg-danger">Inactive</span>';
   //          }
   //       },
   //       {
   //          data: 'update_date',
   //          render: function(data) {
   //             return moment(data).format('DD/MM/YYYY HH:mm');
   //          }
   //       },
   //       {
   //          data: 'update_user_name'
   //       },
   //       {
   //          data: null,
   //          orderable: false,
   //          render: function(data, type, row) {

   //             return ` 
   //                   <a href="${row.toggle_url}" class="btn btn-sm bg-${row.is_active ==="1"? 'secondary' : 'info'} toggle-status">
   //              ${row.is_active ==="0" ?'<i class="fas fa-eye"> </i>': '<i class="fas fa-eye-slash"> </i>'  }
   //                   </a>
   //                   <a href="#"
   //                      class="btn btn-sm btn-primary editButton"
   //                      data-toggle="modal" data-target="#editBankModal"
   //                      data-id="${row.id}"
   //                      data-branch_code="${row.branch_code}"
   //                      data-bank_code="${row.bank_code}"
   //                      data-bank_name="${row.bank_name}"
   //                      data-account_code="${row.account_code}"
   //                      data-account_name="${row.account_name}"
   //                      data-bank_account_no="${row.bank_account_no}"
   //                      data-bank_account_name="${row.bank_account_name}"
   //                      data-bank_address="${row.bank_address}">
   //                      <i class="fas fa-edit"></i>
   //          </a>
   //          <a href="${row.delete_url}" class="btn btn-sm btn-danger deleteButton"><i class="fas fa-trash"></i></a>
   //                  `;
   //          }
   //       }
   //    ],
   //    dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
   //       "<'row'<'col-sm-12'tr>>" +
   //       "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

   //    buttons: [{
   //          extend: 'colvis',
   //          text: 'Columns'
   //       },
   //       {
   //          extend: 'excelHtml5',
   //          text: 'Excel',
   //          className: 'btn-success',
   //          action: function(e, dt, node, config) {
   //             // Get current search/filter values
   //             const searchValue = dt.search();
   //             const order = dt.order();

   //             // Create export URL with parameters
   //             const exportUrl = new URL('<?= site_url('admin/banks/export/excel') ?>', window
   //                .location.href);
   //             exportUrl.searchParams.set('search', searchValue);
   //             exportUrl.searchParams.set('order', JSON.stringify(order));

   //             // Trigger download
   //             window.location = exportUrl.href;
   //          }
   //       },
   //       {
   //          extend: 'pdfHtml5',
   //          text: 'PDF',
   //          className: 'btn-danger',
   //          action: function(e, dt, node, config) {
   //             const searchValue = dt.search();
   //             const order = dt.order();

   //             const exportUrl = new URL('<?= site_url('admin/banks/export/pdf') ?>', window.location
   //                .href);
   //             exportUrl.searchParams.set('search', searchValue);
   //             exportUrl.searchParams.set('order', JSON.stringify(order));

   //             window.location = exportUrl.href;
   //          }
   //       }
   //    ],
   //    colReorder: true,
   //    responsive: true,
   //    pageLength: 20,
   //    order: [
   //       [1, 'ASC'],
   //       [3, 'ASC']
   //    ],
   //    columnDefs: [{
   //          orderable: false,
   //          targets: [10]
   //       } // Disable sorting for action column
   //    ]
   // });
   // Table Event
   // $('#dataGrid').on('click', '.editButton', function() {
   //    const data = $(this).data();
   //    $('#modal-input-id').val(data.id);
   //    $('#modal-input-branch-code').val(data.branch_code);
   //    $('#modal-input-bank-code').val(data.bank_code);
   //    $('#modal-form-mode').val('edit');
   //    $('#modal-input-bank-name').val(data.bank_name);
   //    $('#modal-input-account-code').val(data.account_code);
   //    $('#accountName').val(data.account_name);
   //    $('#modal-input-bank-account-no').val(data.bank_account_no);
   //    $('#modal-input-bank-account-name').val(data.bank_account_name);
   //    $('#modal-input-bank-address').val(data.bank_address);

   // });
});
</script>
<?= $this->endSection() ?>
