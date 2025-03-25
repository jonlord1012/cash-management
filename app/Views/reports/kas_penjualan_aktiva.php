<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kas dari Penjualan Aktiva</h3>
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
            </div>
        </form>

        <div class="px-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td></td>
                        <td>Total</td>
                        <td>Total Rincian</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>kas yang di terima dari penjualan aktiva tetap</td>
                        <td>Rp117,000,000</td>
                    </tr>
                </tbody>
            </table>
        </div>           
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
