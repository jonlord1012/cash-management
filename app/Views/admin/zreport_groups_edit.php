<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Edit Report Group </h3>
   </div>
   <div class="card-body">
      <form action="<?= site_url('admin/rptgroups/update/' . $zreport_groups['id']) ?>" method="post">
         <div class="form-group">
            <label>Group Code</label>
            <input type="text" class="form-control" value="<?= $zreport_groups['group_code'] ?>" readonly>
         </div>

         <div class="form-group">
            <label>Group Name</label>
            <input type="text" name="group_name" class="form-control"
               value="<?= old('group_name', $zreport_groups['group_name']) ?>" required>
         </div>


         <button type="submit" class="btn btn-primary">Update Report Group</button>
         <a href="<?= site_url('/admin/rptgroups') ?>" class="btn btn-default">Cancel</a>
      </form>
   </div>
</div>
<?= $this->endSection() ?>