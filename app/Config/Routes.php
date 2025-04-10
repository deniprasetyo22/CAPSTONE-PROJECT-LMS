<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('home', 'Home::index', ['as' => 'home']);

 /* Public Routes */
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'AuthController::login');
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
});