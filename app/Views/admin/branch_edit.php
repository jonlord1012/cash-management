<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Edit Branch</h3>
   </div>
   <div class="card-body">
      <form action="<?= site_url('admin/branches/update/' . $branch['id']) ?>" method="post">
         <div class="form-group">
            <label>Branch Code</label>
            <input type="text" class="form-control" value="<?= $branch['branch_code'] ?>" readonly>
         </div>

         <div class="form-group">
            <label>Branch Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $branch['name']) ?>" required>
         </div>

         <div class="form-group">
            <div class="custom-control custom-checkbox">
               <input type="checkbox" name="is_head_office" value="1" class="custom-control-input" id="headOfficeCheck"
                  <?= $branch['is_head_office'] ? 'checked' : '' ?>>
               <label class="custom-control-label" for="headOfficeCheck">
                  Head Office
               </label>
            </div>
         </div>

         <button type="submit" class="btn btn-primary">Update Branch</button>
         <a href="<?= site_url('/admin/branches') ?>" class="btn btn-default">Cancel</a>
      </form>
   </div>
</div>
<?= $this->endSection() ?>