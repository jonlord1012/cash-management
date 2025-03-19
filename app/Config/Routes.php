<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
#$routes->get('/login', 'Auth::login');
// Update login routes
$routes->get('login', 'Login::index');
$routes->post('login/auth', 'Login::authenticate');
$routes->get('logout', 'Login::logout');


// Add filter to admin group
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
   # COA 
   $routes->get('coa', 'Admin\Coa::index');
   $routes->post('coa/save', 'Admin\Coa::save');

   # Branches 
   $routes->get('branches', 'Admin\Branches::index');
   $routes->get('branches/create', 'Admin\Branches::create');
   $routes->post('branches/save', 'Admin\Branches::save');
   $routes->get('branches/toggle/(:num)', 'Admin\Branches::toggle/$1');
   $routes->get('branches/edit/(:num)', 'Admin\Branches::edit/$1');
   $routes->post('branches/update/(:num)', 'Admin\Branches::update/$1');

   # USERS
   $routes->get('users', 'Admin\Users::index');
   $routes->post('users/save', 'Admin\Users::save');
   $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');
   $routes->get('audit-logs', 'Admin\AuditLog::index');
});
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

$routes->group('reports', ['filter' => 'auth'], function ($routes) {
   $routes->get('hutang_bank', 'Reports\AccountingReports::hutangBank');
   $routes->get('pemegang_saham', 'Reports\AccountingReports::pemegangSaham');
   $routes->get('kas_hutang_jangka_panjang', 'Reports\AccountingReports::kasHutangJangkaPanjang');
   $routes->get('aktiva_dalam_pembangunan', 'Reports\AccountingReports::activaDalamPembangunan');
});

$routes->group('reports', ['filter' => 'auth'], function ($routes) {
   $routes->get('branch', 'Reports::branchReport');
   $routes->get('consolidated', 'Reports::consolidatedReport');
});