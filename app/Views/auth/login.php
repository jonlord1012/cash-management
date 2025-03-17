<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="login-box">
   <div class="login-logo">
      <b>Accounting</b>System
   </div>
   <div class="card">
      <div class="card-body login-card-body">
         <?php if (session()->getFlashdata('error')): ?>
         <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
         <?php endif; ?>

         <form action="<?= site_url('login/auth') ?>" method="post">
            <div class="input-group mb-3">
               <input type="text" name="username" class="form-control" placeholder="Username" required>
               <div class="input-group-append">
                  <div class="input-group-text">
                     <span class="fas fa-user"></span>
                  </div>
               </div>
            </div>
            <div class="input-group mb-3">
               <input type="password" name="password" class="form-control" placeholder="Password" required>
               <div class="input-group-append">
                  <div class="input-group-text">
                     <span class="fas fa-lock"></span>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-block">Sign In</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
<?= $this->endSection() ?>