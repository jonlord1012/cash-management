<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/* GLOBAL */
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('login', 'Login::index');
$routes->post('login/auth', 'Login::authenticate');
$routes->get('logout', 'Login::logout');
$routes->get('unauthorized', 'Login::unauthorized');
$routes->get('global', 'Reports\AccountingReports::summaryReport');
$routes->get('debug', 'Home::debug');


/* ADMIN */
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
   # COA 
   $routes->get('coa', 'Admin\Coa::index');
   $routes->post('coa/save', 'Admin\Coa::save');
   $routes->get('getcoa', 'Admin\ZReports::getCoa');
   $routes->get('getreportsname', 'Admin\ZReports::getReportsName');
   $routes->get('getgroupsname', 'Admin\ZReports::getReportGroups');

   # Banks 
   $routes->get('banks', 'Admin\Banks::index');
   $routes->get('banks/grid', 'Admin\Banks::renderDataGrid');
   $routes->post('banks/create', 'Admin\Banks::create');
   $routes->post('banks/save', 'Admin\Banks::save');
   $routes->get('banks/toggle/(:any)', 'Admin\Banks::toggle/$1');
   $routes->post('banks/edit/(:any)', 'Admin\Banks::edit/$1');
   $routes->get('banks/delete/(:any)', 'Admin\Banks::delete/$1');
   $routes->get('banks/export/excel', 'Admin\Banks::exportExcel');
   $routes->get('banks/export/pdf', 'Admin\Banks::exportPdf');

   # Branches 
   $routes->get('branches', 'Admin\Branches::index');
   $routes->get('branches/toggle/(:num)', 'Admin\Branches::toggle/$1');
   $routes->get('branches/edit/(:num)', 'Admin\Branches::edit/$1');
   $routes->post('branches/create', 'Admin\Branches::create');
   $routes->post('branches/update/(:num)', 'Admin\Branches::update/$1');
   $routes->post('branches/save', 'Admin\Branches::save');

   # Report Name 
   $routes->get('rptname', 'Admin\ZReports::reportName');
   $routes->get('rptname/toggle/(:num)', 'Admin\ZReports::reportNametoggle/$1');
   $routes->get('rptname/edit/(:any)', 'Admin\ZReports::reportNameedit/$1');
   $routes->post('rptname/create', 'Admin\ZReports::reportNamecreate');
   $routes->post('rptname/update/(:any)', 'Admin\ZReports::reportNameupdate/$1');
   $routes->post('rptname/save', 'Admin\ZReports::reportNamesave');

   # Report Groups 
   $routes->get('rptgroups', 'Admin\ZReports::reportGroups');
   $routes->get('rptgroups/toggle/(:num)', 'Admin\ZReports::reportGroupstoggle/$1');
   $routes->get('rptgroups/edit/(:num)', 'Admin\ZReports::reportGroupsedit/$1');
   $routes->post('rptgroups/create', 'Admin\ZReports::reportGroupscreate');
   $routes->post('rptgroups/save', 'Admin\ZReports::reportGroupssave');
   $routes->post('rptgroups/update/(:num)', 'Admin\ZReports::reportGroupsupdate/$1');

   # Report Settings 
   $routes->get('rptsettings', 'Admin\ZReports::reportSetting');
   $routes->get('rptsettings/toggle/(:num)', 'Admin\ZReports::reportSettingtoggle/$1');
   $routes->get('rptsettings/edit/(:num)', 'Admin\ZReports::reportSettingedit/$1');
   $routes->post('rptsettings/new', 'Admin\ZReports::reportSettingnew');
   $routes->post('rptsettings/update/(:num)', 'Admin\ZReports::reportSettingupdate/$1');
   $routes->post('rptsettings/save', 'Admin\ZReports::reportSettingsave');

   # USERS
   $routes->get('users', 'Admin\Users::index');
   $routes->get('users/delete/(:any)', 'Admin\Users::delete/$1');
   $routes->get('audit-logs', 'Admin\AuditLog::index');
   $routes->post('users/save', 'Admin\Users::save');
});

/* ACCOUNTING */
$routes->group('accounting', ['filter' => 'auth'], function ($routes) {
   $routes->get('journal', 'Accounting::journalEntry');
   $routes->post('journal/save', 'Accounting::saveJournal');

   # Transaction 
   $routes->get('transaction', 'Accounting\Transaction::viewTransaction');
   $routes->get('transaction/new', 'Accounting\Transaction::new');
   $routes->post('transaction/save', 'Accounting\Transaction::save');

   # Utilities
   $routes->get('getcoa', 'Accounting\Transaction::getCoa');
   $routes->get('getbanks', 'Accounting\Transaction::getBanks');
});

/* REPORTS */
$routes->group('reports', ['filter' => 'auth'], function ($routes) {
   $routes->get('/', 'Reports\AccountingReports::summaryReport');
   $routes->get('summary_report', 'Reports\AccountingReports::summaryReport');
   $routes->get('cash_bank', 'Reports\AccountingReports::getCashBankReport');
   $routes->get('arus_kas_breakdown', 'Reports\AccountingReports::getArusKasBreakdown');
   $routes->get('kas_penjualan_aktiva', 'Reports\AccountingReports::getKasPenjualanAktiva');
   $routes->get('hutang_bank', 'Reports\AccountingReports::hutangBank');
   $routes->get('pemegang_saham', 'Reports\AccountingReports::pemegangSaham');
   $routes->get('kas_hutang_jangka_panjang', 'Reports\AccountingReports::kasHutangJangkaPanjang');
   $routes->get('aktiva_dalam_pembangunan', 'Reports\AccountingReports::activaDalamPembangunan');
   $routes->get('getreportcodes', 'Admin\ZReports::getReportsName');
   $routes->get('getreportgroups', 'Admin\ZReports::getReportGroups');
});

$routes->group('reports', ['filter' => 'auth'], function ($routes) {
   $routes->get('branch', 'Reports::branchReport');
   $routes->get('consolidated', 'Reports::consolidatedReport');
});