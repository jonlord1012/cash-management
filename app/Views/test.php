<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
   <div class="container-fluid">
      <h1>AdminLTE Test</h1>

      <!-- Test Font Awesome -->
      <i class="fas fa-check text-success"></i> Font Awesome

      <!-- Test Bootstrap -->
      <button class="btn btn-primary">Bootstrap Button</button>

      <!-- Test AdminLTE -->
      <div class="card">
         <div class="card-body">
            AdminLTE Card
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>