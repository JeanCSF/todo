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
 * Jobs Api Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('all_jobs', 'Api_jobs::index');
$routes->get('job/(:any)', 'Api_jobs::show/$1');
$routes->delete('job_delete/(:any)', 'Api_jobs::delete/$1');
$routes->delete('reply_delete/(:any)', 'Api_jobs::deleteReply/$1');
$routes->get('comment/(:any)', 'Api_jobs::showComment/$1');
$routes->match(['get', 'post'], 'like_content', 'Api_jobs::likeContent');
$routes->match(['get', 'post'], 'comment_content', 'Api_jobs::commentContent');
$routes->match(['get', 'post'], 'create_job', 'Api_jobs::create');
$routes->post('edit_job/(:any)', 'Api_jobs::edit/$1');
$routes->match(['get', 'post'], 'show_likes', 'Api_jobs::showLikes');

/*
 * --------------------------------------------------------------------
 * Users Api Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('profile/(:any)', 'Api_users::show/$1');
$routes->get('user_comments/(:any)', 'Api_users::getReplies/$1');
$routes->get('user_likes/(:any)', 'Api_users::getLikes/$1');
$routes->match(['get', 'post'], 'save_visit', 'Api_users::saveVisit');
$routes->match(['get', 'post'], 'show_visits', 'Api_users::showVisits');


/*
 * --------------------------------------------------------------------
 * Main Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('/', 'Main::main');
$routes->get('home', 'Main::index');
$routes->get('about', 'Main::about');
$routes->get('contact', 'Main::contact');

// $routes->match(['get', 'post'], 'Main/fetchData', 'Main::fetchData');

/*
 * --------------------------------------------------------------------
 * Login Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('signup', 'Logincontroller::signup');
$routes->get('login', 'Logincontroller::login');
$routes->get('logout', 'Logincontroller::logout');

/*
 * --------------------------------------------------------------------
 * Users Controller Routes
 * --------------------------------------------------------------------
 */
$routes->get('user/(:any)', 'Userscontroller::userPage/$1');
$routes->get('users', 'Userscontroller::users');
$routes->post('/upload', 'Userscontroller::upload', ['as' => 'upload']);

/*
 * --------------------------------------------------------------------
 * Todo Controller Routes
 * --------------------------------------------------------------------
 */
$routes->match(['get', 'post'], 'job_likes', 'Todocontroller::countJobLikes');
$routes->get('post/(:any)', 'Todocontroller::job/$1');
$routes->get('reply/(:any)', 'Todocontroller::reply/$1');

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
