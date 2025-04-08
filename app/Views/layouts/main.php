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
   <!-- datatables -->
   <link rel="stylesheet" href="<?= base_url('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
   <link rel="stylesheet" href="<?= base_url('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
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
   <!-- JQuery -->
<script src="<?= base_url('admin/plugins/jquery/jquery.min.js') ?>"></script>
   <!-- Bootstrap 4 -->
<script src="<?= base_url('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
   <!-- AdminLTE -->
<script src="<?= base_url('admin/dist/js/adminlte.min.js') ?>"></script>
   <!-- JStree -->
<script src="<?= base_url('admin/plugins/jstree/jstree.min.js') ?>"></script>
   <!-- JQuery UI -->
<script src="<?= base_url('admin/plugins/jquery-ui/jquery-ui.js') ?>"></script>
   <!-- Popper -->
<script src="<?= base_url('admin/plugins/popper/umd/popper.min.js') ?>" crossorigin="anonymous"></script>
   <!-- Luxon -->
<script src="<?= base_url('admin/dist/js/luxon.min.js') ?>"></script>
   <!-- Moment -->
<script src="<?= base_url('admin/plugins/moment/moment.min.js') ?>"> </script>
   <!-- Datatable  -->
<script src="<?= base_url('admin/plugins/datatables/jquery.dataTables.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-buttons/js/buttons.print.min.js') ?>"> </script>
<script src="<?= base_url('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"> </script>

</script>

</html>
<?= $this->renderSection('scripts') ?>