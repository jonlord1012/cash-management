<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Create New Branch</h3>
   </div>
   <div class="card-body">
      <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <?php if (isset($errors)): ?>
      <div class="alert alert-danger">
         <?php foreach ($errors as $error): ?>
         <?= $error ?><br>
         <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <form action="<?= site_url('admin/branches/save') ?>" method="post">
         <div class="form-group">
            <label>Branch Code</label>
            <input type="text" name="branch_code" class="form-control" pattern="[A-Z0-9]{4,15}"
               title="15 character code" required>
            <small class="form-text text-muted">Unique 4-15character code (e.g., HO01)</small>
         </div>

         <div class="form-group">
            <label>Branch Name</label>
            <input type="text" name="name" class="form-control" required>
         </div>

         <div class="form-group">
            <div class="custom-control custom-checkbox">
               <input type="checkbox" name="is_head_office" value="1" class="custom-control-input" id="headOfficeCheck">
               <label class="custom-control-label" for="headOfficeCheck">
                  Head Office
               </label>
            </div>
         </div>

         <button type="submit" class="btn btn-primary">Create Branch</button>
         <a href="<?= site_url('/admin/branches') ?>" class="btn btn-default">Cancel</a>
      </form>
   </div>
</div>
<?= $this->endSection() ?>