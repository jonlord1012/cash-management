<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Banks Management</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#editBankModal">
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
               <th>COA </th>
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


<!-- Add Bank Modal -->
<div class="modal fade" id="editBankModal" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <form action="<?= site_url('admin/banks/save') ?>" method="post">
            <div class="modal-header">
               <h4 class="modal-title">Bank Management</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <?php if (session()->getFlashdata('success')): ?>
               <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
               <?php endif; ?>
               <?php if (session()->getFlashdata('error')): ?>
               <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
               <?php endif; ?>
            </div>
            <div class="modal-body">

               <div class="form-group">
                  <label>Branch Code</label>
                  <select name="branch_code" class="form-control" id="modal-input-branch-code" required>
                     <option value="">Select Branch</option>
                     <?php foreach ($branches as $branch): ?>
                     <option value="<?= $branch['branch_code'] ?>"><?= $branch['branch_name'] ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="form-group">
                  <label>Bank Code</label>
                  <input type="text" name="bank_code" class="form-control" id="modal-input-bank-code" required>
                  <input type="hidden" name="form_mode" class="form-control" id="modal-form-mode" required>
                  <!-- FORM MODE -->
               </div>

               <div class="form-group">
                  <label>Bank Name</label>
                  <input type="text" name="bank_name" class="form-control" id="modal-input-bank-name" required>
               </div>
               <div class="form-group">
                  <label class="d-sm-block">Akun</label>
                  <input type="text" class="form-control autocomplete-coa col-sm-4 d-sm-inline-block"
                     name="account_code" id="modal-input-account-code" placeholder=" Start typing Coa or name..."
                     autocomplete="off" required>
                  <input type="text" class="form-control col-sm-7 d-sm-inline-block" name="account_name"
                     id="accountName" readonly>
               </div>

               <div class="form-group">
                  <label>Account No</label>
                  <input type="text" class="form-control" name="bank_account_no" placeholder="Type account number"
                     autocomplete="off" id="modal-input-bank-account-no" required>
               </div>
               <div class="form-group">
                  <label>Account Name</label>
                  <input type="text" class="form-control" name="bank_account_name" placeholder="Type account Name"
                     autocomplete="off" id="modal-input-bank-account-name" required>
               </div>
               <div class="form-group">
                  <label>Bank Address</label>
                  <input type="text" class="form-control" name="bank_address" placeholder="Start typing Bank Address"
                     autocomplete="off" id="modal-input-bank-address" required>
               </div>


            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save Bank</button>
            </div>
         </form>
      </div>
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

            data: null,
            render: function(data, type, row) {
               return data.account_code + " -  " + data.account_name;
            }

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
               return data === '1' ?
                  '<span class="badge btn-block bg-success">Active</span>' :
                  '<span class="badge btn-block bg-danger">Inactive</span>';
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
                     <a href="${row.toggle_url}" class="btn btn-sm bg-${row.is_active ==="1"? 'secondary' : 'info'} toggle-status">
                ${row.is_active ==="0" ?'<i class="fas fa-eye"> </i>': '<i class="fas fa-eye-slash"> </i>'  }
                     </a>
                     <a href="#"
                        class="btn btn-sm btn-primary editButton"
                        data-toggle="modal" data-target="#editBankModal"
                        data-id="${row.id}"
                        data-branch_code="${row.branch_code}"
                        data-bank_code="${row.bank_code}"
                        data-bank_name="${row.bank_name}"
                        data-account_code="${row.account_code}"
                        data-account_name="${row.account_name}"
                        data-bank_account_no="${row.bank_account_no}"
                        data-bank_account_name="${row.bank_account_name}"
                        data-bank_address="${row.bank_address}">
                        <i class="fas fa-edit"></i>
            </a>
            <a href="${row.delete_url}" class="btn btn-sm btn-danger deleteButton"><i class="fas fa-trash"></i></a>
                    `;
            }
         }
      ],
      dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

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
         [1, 'ASC'],
         [3, 'ASC']
      ],
      columnDefs: [{
            orderable: false,
            targets: [10]
         } // Disable sorting for action column
      ]
   });
});

/* Edit Modal Trigger */
$(document).ready(function() {
   // Attach event listener on the table for dynamically generated .editButton elements
   $('#dataGrid').on('click', '.editButton', function() {
      const data = $(this).data();
      $('#modal-input-id').val(data.id);
      $('#modal-input-branch-code').val(data.branch_code);
      $('#modal-input-bank-code').val(data.bank_code);
      $('#modal-form-mode').val('edit');
      $('#modal-input-bank-name').val(data.bank_name);
      $('#modal-input-account-code').val(data.account_code);
      $('#accountName').val(data.account_name);
      $('#modal-input-bank-account-no').val(data.bank_account_no);
      $('#modal-input-bank-account-name').val(data.bank_account_name);
      $('#modal-input-bank-address').val(data.bank_address);

   });

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

   // Form Submission
   $('#editBankModal form').on('submit', function(e) {
      e.preventDefault();

      $.ajax({
         type: "POST",
         url: $(this).attr('action'),
         data: $(this).serialize(),
         dataType: 'json', // Ensure expecting JSON response
         success: function(response) {
            if (response.status === 'success') {
               $('#editBankModal').modal('hide');
               if (response.redirect) {
                  window.location.href = response.redirect;
               } else {
                  // For table refresh if using AJAX data
                  location.reload();
               }
            } else {
               // Handle validation errors
               if (response.errors) {
                  Object.keys(response.errors).forEach(function(key) {
                     const $field = $('[name="' + key + '"]');
                     $field.addClass('is-invalid');
                     $field.after(
                        '<div class="invalid-feedback">' +
                        response.errors[key] +
                        '</div>'
                     );
                  });
               }
               alert(response.message || 'An error occurred');
            }
         },
      });
   });
});
</script>
<?= $this->endSection() ?>