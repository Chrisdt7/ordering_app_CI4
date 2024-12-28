<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], 'register', 'Auth::register');
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('profile', 'Users::profile');
$routes->match(['get', 'post'], 'profile/update', 'Users::updateProfile');

// admin
$routes->get('dashboard', 'Admin\Dashboard::index');
$routes->match(['get', 'post'], 'category/add', 'Category::add');
$routes->match(['GET', 'POST'], 'changepassword', 'Users::changePassword');

$routes->post('add-menu', 'Menu::add');
$routes->match(['get', 'post'], 'menu/(:segment)', 'Menu::index/$1');
$routes->match(['get', 'post'], 'update-menu/(:segment)', 'Menu::update/$1');
$routes->match(['get', 'post'], 'delete-menu/(:segment)', 'Menu::delete/$1');

$routes->match(['get', 'post'], 'add-table', 'Tables::add');
$routes->match(['get', 'post'], 'table', 'Tables::index');
$routes->match(['get', 'post'], 'delete-table', 'Menu::delete');