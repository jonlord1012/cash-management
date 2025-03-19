<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <a href="<?= site_url() ?>" class="brand-link">
      <span class="brand-text font-weight-light">Accounting System</span>
   </a>

   <div class="sidebar">
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
               <a href="<?= site_url('dashboard') ?>" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
               </a>
            </li>
            <li class="nav-header">ACCOUNTING</li>
            <li class="nav-item">
               <a href="<?= site_url('accounting/transaction') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Journal Entries</p>
               </a>
            </li>
            <li class="nav-header">REPORTS</li>
            <li class="nav-item">
               <a href="<?= site_url('hutang_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Hutang Bank</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('hutang_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Hutang Bank</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('pemegang_saham') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p> pemegang_saham</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('kas_hutang_jangka_panjang') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>kas_hutang_jangka_panjang</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('aktiva_dalam_pembangunan') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>aktiva_dalam_pembangunan</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('hutang_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Hutang Bank</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('hutang_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Hutang Bank</p>
               </a>
            </li>
            <!-- END REPORTS -->

            <!-- COMPANY -->
            <li class="nav-header">COMPANY</li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/branches/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Branches</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/coa') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>COA</p>
               </a>
            </li>
            <!-- END COMPANY -->
            <!-- SYSTEM -->
            <li class="nav-header">SYSTEM</li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/users/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>User Management</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/audit-logs/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Audit Logs</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('logout') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>LogOut</p>
               </a>
            </li>
         </ul>
      </nav>
   </div>
</aside>