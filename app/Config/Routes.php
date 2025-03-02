<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('auth/login','AuthController::login');
$routes->get('auth/callback','AuthController::callback');
$routes->get('auth/logout', 'AuthController::logout');

$routes->get('/dashboard','DashboardController::index');
$routes->post('/save-phone', 'DashboardController::savePhone');
// $routes->get('/cron','Cron::checkEvents');
