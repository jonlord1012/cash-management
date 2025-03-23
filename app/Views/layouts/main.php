<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?= $title ?? 'Accounting System' ?></title>

   <!-- admin -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/fontawesome-free/css/all.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/dist/css/adminlte.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/dist/css/jambuluwuk.css') ?>">
   <!-- jquery-ui -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/jquery-ui/jquery-ui.css') ?>">
   <!-- jsTree -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/jstree/themes/default/style.min.css') ?>">
   <!-- datepicker -->
   <!--    <link rel="stylesheet"
      href="<?= base_url('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>"
      crossorigin="anonymous"> -->
   <style>
   .ui-autocomplete {
      position: absolute;
      z-index: 1000;
      background: white;
      border: 1px solid #ccc;
      max-height: 200px;
      overflow-y: auto;
   }
   </style>
</head>

<body class="hold-transition sidebar-mini">
   <div class="wrapper">

      <!-- Navbar -->
      <?= $this->include('partials/navbar') ?>

      <!-- Sidebar -->
      <?= $this->include('partials/sidebar') ?>

      <!-- Content Wrapper -->
      <div class="content-wrapper">
         <!-- Content Header -->
         <section class="content-header">
            <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1><?= $title ?? 'Dashboard' ?></h1>
                  </div>
               </div>
            </div>
         </section>

         <!-- Main Content -->
         <section class="content">
            <div class="container-fluid">
               <?= $this->renderSection('content') ?>
            </div>
         </section>
      </div>


      <!-- Footer -->
      <?= $this->include('partials/footer') ?>

   </div>


</body>


<!-- Scripts -->
<script src="<?= base_url('admin/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('admin/dist/js/adminlte.min.js') ?>"></script>
<script src="<?= base_url('admin/plugins/jstree/jstree.min.js') ?>"></script>
<script src="<?= base_url('admin/plugins/jquery-ui/jquery-ui.js') ?>"></script>
<script src="<?= base_url('admin/plugins/popper/umd/popper.min.js') ?>" crossorigin="anonymous"></script>

<script src="<?= base_url('admin/dist/js/luxon.min.js') ?>"></script>
<!-- <script type="text/javascript" src="<?= base_url('admin/dist/js/year_month_picker.js') ?>"></script> -->
<script src="<?= base_url('admin/plugins/moment/moment.min.js') ?>"> </script>
<!-- <script src="<?= base_url('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>" 
crossorigin="anonymous">-->
</script>

</html>
<?= $this->renderSection('scripts') ?>