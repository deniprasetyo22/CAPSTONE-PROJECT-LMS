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

/* Admin Routes */
$routes->group('admin', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::adminDashboard', ['as' => 'admin_dashboard']);

    $routes->group('users', ['namespace' => 'App\Controllers'], function ($routes) {
        $routes->get('index', 'UserController::index', ['as' => 'users']);
        $routes->get('create', 'UserController::create', ['as' => 'create_user']);
        $routes->post('store', 'UserController::store', ['as' => 'store_user']);
        $routes->get('edit/(:num)', 'UserController::edit/$1', ['as' => 'edit_user']);
        $routes->post('update/(:num)', 'UserController::update/$1', ['as' => 'update_user']);
        $routes->delete('delete/(:num)', 'UserController::delete/$1', ['as' => 'delete_user']);
    });
});

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/courses', 'CourseController::index', ['as' => 'courses']);
    $routes->get('/admin/courses', 'CourseController::listCoursesAdmin');
    $routes->get('/courses/add', 'CourseController::addCourseForm');
    $routes->post('/courses/add', 'CourseController::addCourse');
    $routes->get('/courses/edit/(:num)', 'CourseController::editCourseForm/$1', ['as' => 'edit_course']);
    $routes->put('/courses/edit/(:num)', 'CourseController::editCourse/$1');
    $routes->delete('/courses/delete/(:num)', 'CourseController::deleteCourse/$1');
});
