<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Main');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

/*
 * --------------------------------------------------------------------
 * Main Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('/home', 'Main::index');
$routes->get('/test', 'Main::newHome');
$routes->get('/about', 'Main::about');
$routes->get('/contact', 'Main::contact');
$routes->match(['get', 'post'], '/posts', 'Main::loadMoreUsers');
$routes->match(['get', 'post'], '/all', 'Main::indexAjax');
// $routes->match(['get', 'post'], 'Main/fetchData', 'Main::fetchData');

/*
 * --------------------------------------------------------------------
 * Login Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('/signup', 'Logincontroller::signup');
$routes->get('/login', 'Logincontroller::login');
$routes->get('/logout', 'Logincontroller::logout');

/*
 * --------------------------------------------------------------------
 * Users Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('profile/(:any)', 'Userscontroller::profile/$1');
$routes->post('/upload', 'Userscontroller::upload', ['as' => 'upload']);

/*
 * --------------------------------------------------------------------
 * Todo Controller Routes
 * --------------------------------------------------------------------
 */
$routes->match(['get', 'post'], 'job_likes', 'Todocontroller::countJobLikes');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
