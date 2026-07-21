<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================
// MODULE ADMINISTRATEUR (Côté Opérateur)
// ====================================================================

// AUTH ADMIN
$routes->get('/admin/login', 'AdminController::login');
$routes->post('/admin/auth', 'AdminController::authenticate');
$routes->get('/admin/logout', 'AdminController::logout');


$routes->group('', ['filter' => 'adminAuth'], function ($routes) {
    $routes->get('operateur',               'OperateurController::index');
    $routes->get('operateur/new',           'OperateurController::new');
    $routes->post('operateur/create',       'OperateurController::create');
    $routes->get('operateur/edit/(:num)',   'OperateurController::edit/$1');
    $routes->post('operateur/update/(:num)', 'OperateurController::update/$1');
    $routes->get('operateur/delete/(:num)', 'OperateurController::delete/$1');

    $routes->get('prefixe',                 'PrefixeController::index');
    $routes->get('prefixe/new',             'PrefixeController::new');
    $routes->post('prefixe/create',         'PrefixeController::create');
    $routes->get('prefixe/edit/(:num)',     'PrefixeController::edit/$1');
    $routes->post('prefixe/update/(:num)',  'PrefixeController::update/$1');
    $routes->get('prefixe/delete/(:num)',   'PrefixeController::delete/$1');

    $routes->get('operation',               'OperationController::index');

    $routes->get('tarif',                   'TarifController::index');
    $routes->get('tarif/new',               'TarifController::new');
    $routes->post('tarif/create',           'TarifController::create');
    $routes->get('tarif/edit/(:num)',       'TarifController::edit/$1');
    $routes->post('tarif/update/(:num)',    'TarifController::update/$1');
    $routes->get('tarif/delete/(:num)',     'TarifController::delete/$1');

    $routes->get('rapport/gains',           'RapportController::gains');
    $routes->get('rapport/compte',         'RapportController::comptesClients');

    $routes->group('Commission', function ($routes) {
    $routes->get('/', 'CommissionController::index');
    $routes->get('create', 'CommissionController::create');
    $routes->post('store', 'CommissionController::store');
    $routes->get('edit/(:num)', 'CommissionController::edit/$1');
    $routes->post('update/(:num)', 'CommissionController::update/$1');
    $routes->get('delete/(:num)', 'CommissionController::delete/$1');
});

});


$routes->get('/', 'AuthController::login');

$routes->group('Authentification', function ($routes) {
    $routes->get('/', 'AuthController::login');
    $routes->get('login', 'AuthController::login');
    $routes->post('loginAuth', 'AuthController::loginAuth');
    $routes->get('logout', 'AuthController::logout');
});

// Compte
$routes->group('Compte', function ($routes) {
    $routes->get('solde', 'CompteController::solde');
    $routes->get('historique', 'CompteController::historique');
});

// Transactions
$routes->group('Transaction', function ($routes) {
    // Dépôt
    $routes->get('depot', 'TransactionController::depot');
    $routes->post('depot', 'TransactionController::faireDepot');

    // Retrait
    $routes->get('retrait', 'TransactionController::retrait');
    $routes->post('retrait', 'TransactionController::faireRetrait');

    // Transfert
    $routes->get('transfert', 'TransactionController::transfert');
    $routes->post('transfert', 'TransactionController::faireTransfert');
});

// Dashboard
$routes->get('dashboard', 'DashboardController::index');

// Commissions
