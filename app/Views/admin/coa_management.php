<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Chart of Accounts</h3>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCoaModal">
         <i class="fas fa-plus"></i> Add COA
      </button>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>
   </div>
   <div class="card">
      <ul class="nav nav-tabs" id="coaTabs" role="tablist">
         <li class="nav-item">
            <a class="nav-link active" id="grid-tab" data-toggle="tab" href="#gridView" role="tab">Grid View</a>
         </li>
         <li class="nav-item">
            <a class="nav-link " id="tree-tab" data-toggle="tab" href="#treeView" role="tab">Tree View</a>
         </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content mt-3">
         <!-- Grid View Tab -->
         <div class="tab-pane show active" id="gridView" role="tabpanel">
            <div class="table-responsive">
               <table class="table table-bordered table-striped">
                  <thead>
                     <tr>
                        <th>Code</th>
                        <th>Account Name</th>
                        <th>Category</th>
                        <th>Header</th>
                        <th>Total</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     // You'll need to modify your controller to provide flat data
                     foreach ($coaFlatList as $account):
                     ?>
                     <tr>
                        <?php if ($account['category'] != "Header" | $account['category'] != "Total")  $class = 'padding-md'; ?>
                        <td class="<?= $class; ?>"><?= $account['account_code'] ?></td>
                        <td class="<?= $class; ?>"><?= $account['account_name'] ?></td>
                        <td><?= $account['category'] ?></td>
                        <td><?= $account['header_code'] ?></td>
                        <td><?= $account['total_code'] ?></td>
                        <td>
                           <button class="btn btn-sm btn-warning  btn-edit" data-toggle="modal"
                              data-target="#addCoaModal" data-id="<?= $account['id'] ?>"
                              data-code="<?= $account['account_code'] ?>" data-name="<?= $account['account_name'] ?>"
                              data-category="<?= $account['category'] ?>" data-header="<?= $account['header_code'] ?>"
                              data-total="<?= $account['total_code'] ?>">Edit</button>
                           <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         </div>

         <!-- Tree View Tab -->
         <div class="tab-pane fade show" id="treeView" role="tabpanel">
            <div id="coaTree">
               <?= renderCoaTree($coaStructure) ?>
            </div>
         </div>
      </div>


   </div>
   <!-- Add Tab Navigation -->



   <!-- Add COA Modal -->
   <div class="modal fade" id="addCoaModal" role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <form action="<?= site_url('admin/coa/save') ?>" method="post">
               <div class="modal-header">
                  <h4 class="modal-title">COA Management</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>

               </div>
               <div class="modal-body">
                  <div class="form-group">
                     <label>COA Code</label>
                     <input type="text" name="code" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX"
                        id="modal-input-code" required>
                     <small class="form-text text-muted">Example: 1000-0100-0030-0011</small>
                  </div>

                  <div class="form-group">
                     <label>Account Name</label>
                     <input type="text" name="name" class="form-control" id="modal-input-name" required>
                  </div>

                  <div class="form-group">
                     <label>Category</label>
                     <select name="category" class="form-control" id="modal-input-category" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>"><?= $cat ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>


                  <div class="form-group">
                     <label>Header Code</label>
                     <input type="text" class="form-control autocomplete-header" name="header_code"
                        placeholder="Start typing Coa or name..." autocomplete="off" id="modal-input-header-code"
                        required>
                     <input type="text" class="form-control" name="header_name" id="headerName" readonly>
                  </div>

                  <div class="form-group">
                     <label>Total Code</label>
                     <input type="text" class="form-control autocomplete-total" name="total_code"
                        placeholder="Start typing Coa or name..." autocomplete="off" id="modal-input-total-code"
                        required>
                     <input type="text" class="form-control" name="total_name" id="totalName" readonly>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save COA</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <?= $this->endSection() ?>
   <?= $this->section('scripts') ?>
   <script>
   $(document).ready(function() {
      $('#coaTree').jstree({
         'core': {
            'themes': {
               'dots': false,
               'icons': false,
               "responsive": true
            }
         },
         'plugins': ['types'],
         'types': {
            'default': {
               'icon': 'fas fa-folder'
            },
            'Header': {
               'icon': 'fas fa-folder-tree'
            },
            'Detail': {
               'icon': 'fas fa-file-invoice'
            },
            'Total': {
               'icon': 'fas fa-calculator'
            }
         }
      });
   });
   </script>
   <script>
   $(function() {
      $(document).ready(function() {
         // Edit Button Click Event
         $('.btn-edit').on('click', function() {
            const id = $(this).data('id');
            const code = $(this).data('code');
            const name = $(this).data('name');
            const category = $(this).data('category');
            const header = $(this).data('header');
            const total = $(this).data('total');

            // Populate the modal fields with the data
            $('#addCoaModal #modal-input-id').val(id); // If you have a hidden ID field
            $('#addCoaModal #modal-input-code').val(code); // If you have a hidden ID field
            $('#addCoaModal #modal-input-name').val(name);
            $('#addCoaModal #modal-input-category').val(category);
            $('#addCoaModal #modal-input-header-code').val(header);
            $('#addCoaModal #modal-input-total-code').val(total);

            // Apply readonly state based on category
            adjustReadonlyFields(category);

            // Show the modal
            $('#addCoaModal').modal('show');

            // Change event for Category dropdown
            $('#addCoaModal #modal-input-category').on('change', function() {
               const selectedCategory = $(this).val();
               adjustReadonlyFields(selectedCategory);
            });

            // Toggle readonly state based on category
            function adjustReadonlyFields(category) {
               if (category === 'Header') {
                  $('#addCoaModal #modal-input-header-code').prop('readonly',
                     true); // Disable header_code
                  $('#addCoaModal #modal-input-total-code').prop('readonly',
                     false); // Enable total_code
               } else if (category === 'Total') {
                  $('#addCoaModal #modal-input-total-code').prop('readonly',
                     true); // Disable total_code
                  $('#addCoaModal #modal-input-header-code').prop('readonly',
                     false); // Enable header_code
               } else {
                  $('#addCoaModal #modal-input-header-code').prop('readonly', false); // Enable both
                  $('#addCoaModal #modal-input-total-code').prop('readonly', false);
               }
            }
         });
      });
      // COA Autocomplete
      $(document).ready(function() {
         // COA Autocomplete
         $('.autocomplete-header').autocomplete({
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
            appendTo: "body",
            minLength: 2,
            select: function(event, ui) {
               if (!ui.item) {
                  console.error('Invalid selection');
                  return false;
               }
               $('#headerName').val(ui.item.account_name);
               $('[name="header_code"]').val(ui.item.account_code).trigger('change');
               return false;
            }

         }).autocomplete('instance')._renderItem = function(ul, item) {
            return $('<li>')
               .append(`<div>${item.account_code} - ${item.account_name}</div>`)
               .appendTo(ul);
         };

         $('.autocomplete-total').autocomplete({
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
            appendTo: "body",
            minLength: 2,
            select: function(event, ui) {
               if (!ui.item) {
                  console.error('Invalid selection');
                  return false;
               }
               $('#totalName').val(ui.item.account_name);
               $('[name="total_code"]').val(ui.item.account_code).trigger('change');
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
      $('#addCoaModal form').on('submit', function(e) {
         e.preventDefault();

         // Get the selected category
         const category = $('#addCoaModal #modal-input-category').val();

         // Remove values based on category
         if (category === 'Header') {
            $('#addCoaModal #modal-input-header-code').val(''); // Clear total_code
            $('#addCoaModal #headerName').val(''); // Clear total_name if present
         } else if (category === 'Total') {
            $('#addCoaModal #modal-input-total-code').val(''); // Clear header_code
            $('#addCoaModal #totalName').val(''); // Clear header_name if present
         }
         $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json', // Ensure expecting JSON response
            success: function(response) {
               if (response.status === 'success') {
                  $('#addCoaModal').modal('hide');
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