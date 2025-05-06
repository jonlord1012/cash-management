<nav class="main-header navbar navbar-expand navbar-white navbar-light">
   <ul class="navbar-nav">
      <li class="nav-item">
         <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
         </a>
      </li>
      <li class="nav-item">
         <h3>Branch Name : <?= getBranchNameByUserCode(session()->get('username'));  ?></h3>
      </li>
   </ul>

   <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
         <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user m-1"></i> <span
               class="d-none d-md-inline right"><?= (session()->get('username')) ?></span>
         </a>
         <div class="dropdown-menu dropdown-menu-right">
            <a href="<?= site_url('admin/users/edit') ?>" class="dropdown-item">
               <i class="fas fa-user p-1"></i> <span class="d-none d-md-inline right">Profile</span>
            </a>
            </a>
            <a href="<?= site_url('admin/users/edit') ?>" class="dropdown-item">
               <i class="fas fa-cogs p-1"></i> <span class="d-none d-md-inline right">Settings</span>
            </a>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= site_url('logout') ?>" class="dropdown-item">
               <i class="fas fa-sign-out-alt m-1"></i><span class="d-none d-md-inline right">Logout</span>
            </a>
            </a>
         </div>
      </li>
   </ul>
</nav>