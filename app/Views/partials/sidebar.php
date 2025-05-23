<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <a href="<?= site_url() ?>" class="brand-link">
      <span class="brand-text font-weight-light">Accounting System</span>
   </a>

   <div class="sidebar">
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
               <a href="<?= site_url('dashboard') ?>" class="nav-link">
                  <i class="nav-icon fas fa-chart-line"></i>
                  <p>Dashboard</p>
               </a>
            </li>
            <!-- ACCOUNTING MENU-->
            <li class="nav-header">ACCOUNTING</li>
            <li class="nav-item">
               <a href="<?= site_url('accounting/transaction') ?>" class="nav-link">
                  <i class="nav-icon fas fa-money-check-alt"></i>
                  <p>Journal Entries</p>
               </a>
            </li>
            <!-- END ACCOUNTING MENU-->
            <!-- REPORTS MENU -->
            <li class="nav-header">REPORTS</li>
            <li class="nav-item">
               <a href="<?= site_url('reports/summary_report') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Summary Input</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/cash_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Cash/Bank</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/arus_kas_breakdown') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Arus Kas (Breakdown)</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('hutang_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Arus Kas (KP)</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('hutang_bank') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Rincian Cash Flow</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/kas_penjualan_aktiva') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Kas Penjualan Aktiva</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/aktiva_tetap') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Aktiva Tetap</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/aktiva_pembangunan') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Aktiva Dalam Pembangunan</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('reports/kas_hutang_jangka_panjang') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Kas Hutang Jangka Panjang</p>
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
                  <p> Pemegang Saham</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('kas_hutang_jangka_panjang') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Biaya Promosi</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('aktiva_dalam_pembangunan') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Biaya ADM & Umum Lainnya</p>
               </a>
            </li>
            <!-- END REPORTS -->

            <!-- COMPANY -->
            <li class="nav-header">COMPANY</li>

            <li class="nav-item">
               <a href="<?= site_url('/admin/coa') ?>" class="nav-link">
                  <i class="nav-icon fas fa-user-tie"></i>
                  <p>COA</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/banks/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-landmark"></i>
                  <p>Banks</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/branches/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-hotel"></i>
                  <p>Branches</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/rptname/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Report Name</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/rptgroups/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Report Group</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/rptsettings/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Report Setting</p>
               </a>
            </li>
            <!-- END COMPANY -->
            <!-- SYSTEM -->
            <li class="nav-header">SYSTEM</li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/users/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-users"></i>
                  <p>User Management</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('/admin/audit-logs/') ?>" class="nav-link">
                  <i class="nav-icon fas fa-clipboard-list"></i>
                  <p>Audit Logs</p>
               </a>
            </li>
            <li class="nav-item">
               <a href="<?= site_url('logout') ?>" class="nav-link">
                  <i class="nav-icon fas fa-sign-out-alt"></i>
                  <p>Sign Out</p>
               </a>
            </li>
         </ul>
      </nav>
   </div>
</aside>