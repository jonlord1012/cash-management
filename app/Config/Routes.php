<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
#$routes->get('/', 'Home::index');

$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');


// Update login routes
$routes->get('login', 'Login::index');
$routes->post('login/auth', 'Login::authenticate');
$routes->get('logout', 'Login::logout');


// Add filter to admin group
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
   $routes->get('coa', 'Admin\Coa::index');
   $routes->post('coa/save', 'Admin\Coa::save');
   $routes->get('branches', 'Admin\Branches::index');
   $routes->get('branches/toggle/(:num)', 'Admin\Branches::toggle/$1');
   $routes->get('branches/edit/(:num)', 'Admin\Branches::edit/$1');
   $routes->post('branches/update/(:num)', 'Admin\Branches::update/$1');
});
$routes->group('accounting', ['filter' => 'auth'], function ($routes) {
   $routes->get('journal', 'Accounting::journalEntry');
   $routes->post('journal/save', 'Accounting::saveJournal');
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