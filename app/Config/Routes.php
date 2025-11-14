<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('dbtest', 'Home::dbtest');
$routes->get('login',    'Auth::login');
$routes->post('login',   'Auth::attemptLogin');
$routes->get('signup',   'Auth::signup');
$routes->post('signup',  'Auth::register');
$routes->get('logout',   'Auth::logout');
//$routes->setAoutoRoute(true);