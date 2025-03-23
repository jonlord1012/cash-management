<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
   <div class="card-header">
      <h3 class="card-title">Edit Report Name</h3>
   </div>
   <div class="card-body">
      <form action="<?= site_url('admin/rptname/update/' . $zreports_name['id']) ?>" method="post">
         <div class="form-group">
            <label>Report Code</label>
            <input type="text" class="form-control" value="<?= $zreports_name['report_code'] ?>" readonly>
         </div>

         <div class="form-group">
            <label>Report Name</label>
            <input type="text" name="report_name" class="form-control"
               value="<?= old('report_name', $zreports_name['report_name']) ?>" required>
         </div>


         <button type="submit" class="btn btn-primary">Update Report Name</button>
         <a href="<?= site_url('/admin/rptname') ?>" class="btn btn-default">Cancel</a>
      </form>
   </div>
</div>
<?= $this->endSection() ?>