<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentification
$routes->get('/', 'AuthController::login');

$routes->group('Authentification', function ($routes) {
    $routes->get('/', 'AuthController::login');
    $routes->get('login', 'AuthController::login');
    $routes->post('loginAuth', 'AuthController::loginAuth');
    $routes->get('logout', 'AuthController::logout');
});

// Compte
$routes->group('Compte', ['filter' => 'auth'], function ($routes) {
    $routes->get('solde', 'CompteController::solde');
    $routes->get('historique', 'CompteController::historique');
});

// Transactions
$routes->group('Transaction', ['filter' => 'auth'], function ($routes) {
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
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);