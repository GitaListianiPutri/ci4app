<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// akses met get, /->route:bse url(localhost:8080) 
//  controller pages methodnya index
$routes->get('/', 'Pages::index');
// $routes->get('/pages', 'Pages::index');
$routes->get('/about', 'Pages::about');
$routes->get('/contact', 'Pages::contact');
$routes->get('/komik', 'Komik::index');
$routes->get('/komik/create', 'Komik::create');
$routes->post('/komik/save', 'Komik::save');
$routes->get('/komik/edit/(:segment)', 'Komik::edit/$1');
$routes->post('/komik/update/(:num)', 'Komik::update/$1');
$routes->get('/komik/(:any)', 'Komik::detail/$1');
$routes->delete('/komik/(:num)', 'komik::delete/$1');
 
// url part : localhost/80:80/komik
// routeurl : localhost/
// ANY, SEGMENT => PLACEHOLDER