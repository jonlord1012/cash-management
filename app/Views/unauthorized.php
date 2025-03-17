<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="error-page">
   <h2 class="headline text-danger">403</h2>
   <div class="error-content">
      <h3><i class="fas fa-exclamation-triangle text-danger"></i> Access Denied</h3>
      <p>
         You don't have permission to access this resource.
         <a href="<?= site_url('/') ?>">Return to dashboard</a>
      </p>
   </div>
</div>
<?= $this->endSection() ?>