<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Home::login');
$routes->post('login', 'Home::login');
$routes->get('logout', 'Home::logout');
$routes->get('dbtest', 'Home::dbtest');
$routes->get('signup', 'Home::signup');
//$routes->setAoutoRoute(true);