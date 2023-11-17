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
$routes->group('api/job', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->put('update/(:num)', 'Api_jobs::update/$1');
    $routes->put('update_reply/(:num)', 'Api_jobs::updateReply/$1');
    $routes->get('all', 'Api_jobs::index');
    $routes->get('show/(:num)', 'Api_jobs::show/$1');
    $routes->post('finish/(:num)', 'Api_jobs::finish/$1');
    $routes->delete('delete/(:num)', 'Api_jobs::delete/$1');
    $routes->get('reply/(:num)', 'Api_jobs::showReply/$1');
    $routes->post('like', 'Api_jobs::likeContent');
    $routes->post('comment', 'Api_jobs::commentContent');
    $routes->post('create', 'Api_jobs::create');
    $routes->post('likes', 'Api_jobs::showLikes');
});

/*
 * --------------------------------------------------------------------
 * Users Api Controller Routes
 * --------------------------------------------------------------------
 */
$routes->group('api/user', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->get('show/(:any)', 'Api_users::show/$1');
    $routes->get('replies/(:num)', 'Api_users::getReplies/$1');
    $routes->get('liked/(:num)', 'Api_users::getLikes/$1');
    $routes->post('save_visit', 'Api_users::saveVisit');
    $routes->post('visits', 'Api_users::showVisits');
});


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
$routes->get('post/(:any)', 'Todocontroller::job/$1');
$routes->get('reply/(:any)', 'Todocontroller::reply/$1');

/*
 * --------------------------------------------------------------------
 * Chat Controller Routes
 * --------------------------------------------------------------------
 */
$routes->group('messages', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Chatcontroller::index');
    $routes->get('get_messages/(:num)', 'Chatcontroller::getMessages/$1');
    $routes->get('get_last_message/(:num)', 'Chatcontroller::getLastChatMessage/$1');
    $routes->get('get_chats/(:num)', 'Chatcontroller::getChats/$1');
    $routes->get('chat/(:any)', 'ChatController::chat/$1');
    $routes->post('new_chat', 'ChatController::createChat');
    $routes->post('send_message', 'ChatController::sendMessage');
});

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
