<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Pages::home');
$routes->get('/dashboard', 'Pages::dashboard');
$routes->get('/laboratory', 'Pages::laboratory');
$routes->get('/pharmacy', 'Pages::pharmacy');
$routes->get('/scheduling', 'Pages::scheduling');
$routes->get('/billing', 'Pages::billing');

// Auth and misc pages
$routes->get('/about', 'Pages::about');
$routes->get('/login', 'Pages::login');
$routes->get('/register', 'Pages::register');

// Dashboard pages
$routes->get('/records', 'Pages::records');
$routes->get('/reports', 'Pages::reports');
