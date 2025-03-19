<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Cash Management</h3>
   </div>
   <div class="card-header warning">
      <h3 class="card-title">Ref No : &nbsp;</h3> <input type="text" class="form-control" name="refNo"
         value="<?= $refNo; ?>" readonly>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>
      <div class="card card-warning">
         <div class="card-header">
            <h3 class="card-title"> Input Transaksi </h3>
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
                        <input type="text" class="form-control autocomplete-coa" name="account_code"
                           placeholder="Start typing Coa or name..." autocomplete="off" required>
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
                        <input type="text" class="form-control autocomplete-source" name="bank_code"
                           placeholder="Start typing bank code or name..." autocomplete="off" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <label>Nama Source</label>
                     <!--<div id="sourceName" class="form-control-static"></div>-->
                     <input type="text" class="form-control" name="bank_name" id="sourceName" readonly>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-12">
                     <div class="form-group">
                        <label>Note</label>
                        <input type="text" class="form-control" placeholder="Masukan Deskripsi" name="description" />
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Cek/BG</label>
                        <input type="text" class="form-control" placeholder="masukan nomor Cek/BG" name="document_no" />
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Pemasukan</label>
                        <input type="text" class="form-control" placeholder="0" name="debit" />
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label>Pengeluaran</label>
                        <input type="text" class="form-control" placeholder="0" name="credit" />
                     </div>
                  </div>
               </div>

               <button type="submit" class="btn btn-primary float-right">
                  <i class="fas fa-plus"></i> Save Transaction
               </button>
            </form>
         </div>
      </div>

      <div class="mt-4">
         <!-- start grid -->
         <table class="table table-bordered" id="transactionsTable">
            <thead>
               <tr>
                  <th>Ref No</th>
                  <th>Account</th>
                  <th>Source</th>
                  <th>Note</th>
                  <th>Cek/BG</th>

                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Date</th>
                  <th colspan="2">Actions</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($transactions as $t): ?>
               <tr>
                  <td><?= $t['ref_no'] ?></td>
                  <td><?= $t['account_code'] ?> - <?= $t['account_name'] ?></td>
                  <td><?= $t['bank_code'] ?> - <?= $t['bank_name'] ?></td>
                  <td><?= $t['description'] ?></td>
                  <td><?= $t['doc_no'] ?> </td>
                  <td><?= number_format($t['debit']) ?></td>
                  <td><?= number_format($t['credit']) ?></td>
                  <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
                  <td>
                     <button class="btn btn-sm btn-warning edit-btn" data-id="<?= $t['ref_no'] ?>">Edit</button>
                  </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div> <!-- end grid -->


   </div>
</div>

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
});
</script>

<?= $this->endSection() ?>