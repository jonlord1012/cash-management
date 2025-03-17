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

      <div id="coaTree">
         <?= renderCoaTree($coaStructure) ?>
      </div>
   </div>
</div>

<!-- Add COA Modal -->
<div class="modal fade" id="addCoaModal">
   <div class="modal-dialog">
      <div class="modal-content">
         <form action="<?= site_url('admin/coa/save') ?>" method="post">
            <div class="modal-header">
               <h4 class="modal-title">Add New COA</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
               <div class="form-group">
                  <label>COA Code</label>
                  <input type="text" name="code" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX" required>
                  <small class="form-text text-muted">Example: 1000-0100-0030-0011</small>
               </div>

               <div class="form-group">
                  <label>Account Name</label>
                  <input type="text" name="name" class="form-control" required>
               </div>

               <div class="form-group">
                  <label>Category</label>
                  <select name="category" class="form-control" required>
                     <option value="">Select Category</option>
                     <?php foreach ($categories as $cat): ?>
                     <option value="<?= $cat ?>"><?= $cat ?></option>
                     <?php endforeach; ?>
                  </select>
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
      "core": {
         "themes": {
            "responsive": false
         }
      },
      "types": {
         "default": {
            "icon": "fas fa-folder"
         },
         "file": {
            "icon": "fas fa-file"
         }
      },
      "plugins": ["types"]
   });
});
</script>
<?= $this->endSection() ?>