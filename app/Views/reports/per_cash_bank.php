<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Laporan per Cash / Bank</h3>
   </div>
   <div class="card-header">
      <form id="perCashBankReport" action="<?= site_url('reports/cash_bank') ?>" method="get">
         <div class="row">
            <div class="col-sm-3">
               <div class="form-group">
                  <label>Branch</label>
                  <!-- <input type="text" class="form-control autocomplete-source" name="bank_code" required> -->
                  <input type="text" class="form-control autocomplete-branch" name="branch_code" id="branchCode"
                     placeholder="Start typing branch code or name..." autocomplete="off" required>
               </div>
            </div>
            <div class="col-sm-3">
               <div class="form-group">
                  <label>Source</label>
                  <!-- <input type="text" class="form-control autocomplete-source" name="bank_code" required> -->
                  <input type="text" class="form-control autocomplete-source" name="bank_code" id="bankCode"
                     placeholder="Start typing bank code or name..." autocomplete="off" required>
               </div>
            </div>

            <div class="col-sm-3">
               <div class="form-group">
                  <label>Periode</label>
                  <!-- <input type="text" class="form-control autocomplete-source" name="bank_code" required> -->
                  <input type="text" class="form-control autocomplete-periode" name="periode_code" id="periodeCode"
                     placeholder="Start typing Periode..." autocomplete="off" required>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-3">
               <label>Branch Name</label>
               <!--<div id="sourceName" class="form-control-static"></div>-->
               <input type="text" class="form-control" name="name" id="branchName" readonly>
            </div>
            <div class="col-sm-3">
               <label>Bank/Source Name</label>
               <!--<div id="sourceName" class="form-control-static"></div>-->
               <input type="text" class="form-control" name="bank_name" id="sourceName" readonly>
            </div>

            <div class="col-sm-3">
               <div class="form-group">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-success btn-block "><i class="fa fa-passport">&nbsp;</i> <strong>
                        Generate Report</strong></button>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
<div class="card card-info card-outline">
   <div class="card-body card-info card-outline">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>
      <table class="table table-bordered table-striped" width="100%" id="dataGridCashBank">
         <thead>
            <tr>
               <th>Date</th>
               <th>Account</th>
               <th>Name</th>
               <th>Cabang</th>
               <th>Source</th>
               <th>Note</th>
               <th>Cek/BG </th>
               <th>Opening</th>
               <th>Debit</th>
               <th>Credit</th>
               <th>Ending</th>
               <th>Posted</th>
               <th>Update Date</th>
               <th>Update By</th>
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
   $('#perCashBankReport').on('submit', function(e) {
      e.preventDefault(); // stop actual submit
      $('#dataGridCashBank').DataTable().ajax.reload(); // reload datatable with new filters
   });

   $('#dataGridCashBank').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
         "url": '<?= site_url('reports/cash_bank/grid') ?>',
         "type": "GET",
         "data": function(d) {
            // Add filters if necessary
            d.branchCode = $('#branchCode').val();
            d.bankCode = $('#bankCode').val();
            d.periodeCode = $('#periodeCode').val();
         }
      },
      columns: [{
            "data": "transaction_date"
         },
         {
            "data": "account_code"
         },
         {
            "data": "account_name"
         },
         {
            "data": "short_name"
         },
         {
            "data": "bank_name"
         },
         {
            "data": "description"
         },
         {
            "data": "doc_no"
         },
         {
            "data": "begining_balance"
         },
         {
            "data": "debit"
         },
         {
            "data": "credit"
         },
         {
            "data": "ending_balance"
         },
         {
            "data": "is_posted"
         },
         {
            "data": "update_date"
         },
         {
            "data": "update_user_name"
         }
      ],
      "language": {
         "paginate": {
            "next": "Next ➡️",
            "previous": "⬅️ Previous"
         }
      },
      order: [
         [0, 'asc']
      ],
      colReorder: true,
      responsive: true,
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
   });

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
      //console.log('Rendering item:', item); // Moved before return
      return $('<li>')
         .append(`<div>${item.bank_code} - ${item.bank_name}</div>`)
         .appendTo(ul);
   };

   // Branch Autocomplete
   $('.autocomplete-branch').autocomplete({
      source: function(request, response) {
         $.ajax({
            url: '<?= site_url('accounting/getbranches') ?>',
            dataType: 'json',
            data: {
               term: request.term
            },
            success: function(data) {
               response(data);
            },
            error: function(xhr) {
               console.error('Branches Search Error:', xhr.responseText);
            }
         });
      },
      minLength: 2,
      select: function(event, ui) {
         console.log('Selected Branch:', ui.item);
         $('#branchName').val(ui.item.name);
         $('[name="branch_code"]').val(ui.item.branch_code).trigger('change');
         return false;
      }
   }).autocomplete('instance')._renderItem = function(ul, item) {
      return $('<li>')
         .append(`<div>${item.branch_code} - ${item.short_name} - ${item.name} </div>`)
         .appendTo(ul);
   };

   // Periode Autocomplete
   $('.autocomplete-periode').autocomplete({
      source: function(request, response) {
         $.ajax({
            url: '<?= site_url('accounting/getperiodes') ?>',
            dataType: 'json',
            data: {
               term: request.term
            },
            success: function(data) {
               response(data);
            },
            error: function(xhr) {
               console.error('Periode Search Error:', xhr.responseText);
            }
         });
      },
      minLength: 2,
      select: function(event, ui) {
         console.log('Selected Periode:', ui.item);
         $('.periode_code').val(ui.item.periode_code);
         $('[name="periode_code"]').val(ui.item.periode_code).trigger('change');
         return false;
      }
   }).autocomplete('instance')._renderItem = function(ul, item) {
      return $('<li>')
         .append(`<div>${item.periode_code} -  ${item.periode_code} </div>`)
         .appendTo(ul);
   };
});
</script>

<?= $this->endSection() ?>