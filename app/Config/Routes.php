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
$routes->group('admin', ['filter' => 'role:administrator'], ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::adminDashboard', ['as' => 'admin_dashboard']);

    /* User */
    $routes->group('users', function ($routes) {
        $routes->get('index', 'UserController::index', ['as' => 'users']);
        $routes->get('show/(:num)', 'UserController::show/$1', ['as' => 'show_user']);
        $routes->get('create', 'UserController::create', ['as' => 'create_user']);
        $routes->post('store', 'UserController::store', ['as' => 'store_user']);
        $routes->get('edit/(:num)', 'UserController::edit/$1', ['as' => 'edit_user']);
        $routes->put('update/(:num)', 'UserController::update/$1', ['as' => 'update_user']);
        $routes->delete('delete/(:num)', 'UserController::delete/$1', ['as' => 'delete_user']);
    });

    /* Level */
    $routes->group('levels', function ($routes) {
        $routes->get('index', 'LevelController::index', ['as' => 'levels']);
        $routes->get('create', 'LevelController::create', ['as' => 'create_level']);
        $routes->post('store', 'LevelController::store', ['as' => 'store_level']);
        $routes->get('edit/(:num)', 'LevelController::edit/$1', ['as' => 'edit_level']);
        $routes->put('update/(:num)', 'LevelController::update/$1', ['as' => 'update_level']);
        $routes->delete('delete/(:num)', 'LevelController::delete/$1', ['as' => 'delete_level']);
    });
});

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/courses', 'CourseController::index', ['as' => 'courses']);
    $routes->get('/admin/courses', 'CourseController::listCoursesAdmin', ['as' => 'list_courses']);
    $routes->get('/courses/add', 'CourseController::addCourseForm');
    $routes->post('/courses/add', 'CourseController::addCourse');
    $routes->get('/courses/edit/(:num)', 'CourseController::editCourseForm/$1', ['as' => 'edit_course']);
    $routes->put('/courses/edit/(:num)', 'CourseController::editCourse/$1');
    $routes->delete('/courses/delete/(:num)', 'CourseController::deleteCourse/$1');
});

/* Student Routes */
$routes->group('student', ['filter' => 'role:student'], ['namespace' => 'App\Controllers'], function ($routes) {
    /* Enrollemnt */
    $routes->group('enrollment', function ($routes) {
        $routes->post('store/(:num)', 'EnrollmentController::store/$1', ['as' => 'store_enrollment']);
    });

    /* Courses */
    $routes->group('courses', function ($routes) {
        $routes->get('index', 'CourseController::studentCourseList', ['as' => 'student_courses']);
        $routes->get('my-courses', 'CourseController::myCourses', ['as' => 'my_courses']);
    });


});