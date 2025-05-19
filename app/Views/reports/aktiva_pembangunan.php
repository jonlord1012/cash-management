<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- FILTERING -->
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Aktiva Pembangunan</h3>
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
                    <h3 class="font-weight-bold">Rp2,146,763,003</h3>
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
                    <h6 class="text-black-50 font-weight-light">KAS DIBAYAR UNTUK PEROLEHAN AKTIVA DALAM PEMBANGUNAN</h6>
                    <h3 class="font-weight-bold">Rp. 2,146,763,003</h3>
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
<!-- DATATABLE -->
<div class="card">
   <div class="card-body card-info card-outline">
      <table class="table table-bordered" id="dataGridAktivaPembangunan">
         <thead>
            <tr>
               <th>Tanggal</th>
               <th>Keterangan</th>
               <th>Keterangan</th>
               <th>Amount</th>
               <th>Ke Bank</th>
               <th>Amount</th>
            </tr>
         </thead>
         <tbody></tbody>
      </table>
   </div>
</div>
<!-- END OF DATATABLE -->
<?= $this->endSection() ?>
<!-- ======================= -->
<?= $this->section('scripts') ?>
<script>
$(document).ready(function(){
   $('#dataGridAktivaPembangunan').DataTable({
      processing: true,
      serverSide: true,
      deferLoading: 0, // Don't load initially
      // dom:  "<'row'<'col-md-2'l><'col-md-2'B>>",
      dom: 'Bfrtip',
      buttons:[
         {
            extend: 'collection',
            text: '<i class="fas fa-download"></i> Export to',
            className: 'btn-export-dropdown btn-info btn-sm',
            buttons: [
               {
                  extend: 'excel',
                  text: '<i class="fas fa-file-excel"></i> Excel',
                  className: 'btn-excel-report',
                  exportOptions: {
                     columns: ':visible'
                  },
                  customize: function(xlsx) {
                     var sheet = xlsx.xl.worksheets['sheet1.xml'];
                     $('row c[r^="A"]', sheet).attr('s', '2'); // Bold headers
                  }
               },
               {
                  extend: 'pdf',
                  text: '<i class="fas fa-file-pdf"></i> PDF',
                  className: 'btn-pdf-report',
                  exportOptions: {
                     columns: ':visible'
                  },
                  customize: function(doc) {
                     doc.defaultStyle.fontSize = 10;
                     doc.styles.tableHeader.fontSize = 11;
                     doc.styles.title.fontSize = 12;
                     doc.content[1].table.widths = 
                     Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                  }
               },
            ],
            fade: true,
            dropup: true,
         }
      ]
   })
})

// COA
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
});
</script>
<?= $this->endSection() ?>

