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
});

/* Student Routes */
$routes->group('student', ['filter' => 'role:student'], ['namespace' => 'App\Controllers'], function ($routes) {
    /* Enrollemnt */
    $routes->group('enrollment', function ($routes) {
        $routes->post('store/(:num)', 'EnrollmentController::store/$1', ['as' => 'store_enrollment']);
        $routes->delete('leave/(:num)', 'EnrollmentController::leaveCourse/$1', ['as' => 'leave_course']);
    });

    /* Courses */
    $routes->group('courses', function ($routes) {
        $routes->get('index', 'CourseController::studentCourseList', ['as' => 'student_courses']);
        $routes->get('my-courses', 'CourseController::myCourses', ['as' => 'my_courses']);
        $routes->get('show/(:num)', 'CourseController::show/$1', ['as' => 'show_course']);
        $routes->get('show-content/(:num)/(:num)', 'CourseController::showContent/$1/$2', ['as' => 'show_course_detail']);
        $routes->get('file/(:segment)', 'CourseController::file/$1', ['as' => 'file']);
    });
});

$routes->group('', ['filter' => 'role:teacher'], ['namespace' => 'App\Controllers'], function ($routes) {
    /* Get a list of a course based on logged in teacher*/
    $routes->get('/course-lecturers', 'CourseLecturerController::lecturerCourseList', ['as' => 'lecturer_courses']);
    $routes->get('/course-lecturers-archived', 'CourseLecturerController::lecturerCourseListArchived', ['as' => 'archived_lecturer_courses']);
    $routes->get('/courses/detail/(:num)', 'CourseController::detailCourse/$1', ['as' => 'course_detail']);
    $routes->get('/courses/add', 'CourseController::addCourseForm');
    $routes->post('/courses/add', 'CourseController::addCourse');
    $routes->get('/courses/edit/(:num)', 'CourseController::editCourseForm/$1', ['as' => 'edit_course']);
    $routes->put('/courses/edit/(:num)', 'CourseController::editCourse/$1');
    $routes->delete('/courses/delete/(:num)', 'CourseController::deleteCourse/$1');
    /* Add or Remove User to become a student or teacher at a course*/
    $routes->get('/search-users/(:num)', 'UserController::showStudentLists/$1');
    $routes->post('enroll_student/(:num)', 'EnrollmentController::addNewStudent/$1');
    $routes->post('add_lecturer_course/(:num)', 'CourseLecturerController::addNewLecturer/$1');
    $routes->delete('remove_lecturer_course/(:num)', 'CourseLecturerController::removeLecturer/$1');
    $routes->delete('remove_student_course/(:num)', 'EnrollmentController::removeStudent/$1');

    /* Add,Edit or Remove Content of a course*/
    $routes->get('course-content/add/(:num)', 'CourseContentController::addContentForm/$1');
    $routes->post('course-content/add/(:num)', 'CourseContentController::addContent/$1');
    $routes->get('course-content/edit/(:num)', 'CourseContentController::editContentForm/$1');
    $routes->put('course-content/edit/(:num)', 'CourseContentController::editContent/$1');
    $routes->delete('course-content/delete/(:num)', 'CourseContentController::deleteContent/$1');
    $routes->get('course-content/(:num)/(:num)', 'CourseContentController::showContent/$1/$2');

    /* Add,Edit or Remove File of a course*/
    $routes->get('file-material/(:any)', 'CourseContentController::showFileContent/$1');
    $routes->get('file-form/(:num)', 'CourseContentController::addFileContentForm/$1');
    $routes->post('file/(:num)', 'CourseContentController::addFileContent/$1');
    $routes->delete('file/(:num)', 'CourseContentController::deleteFileContent/$1');
    $routes->get('file-form-edit/(:any)', 'CourseContentController::editFileContentForm/$1');
    $routes->put('file-edit/(:any)', 'CourseContentController::editFileContent/$1');

    /* Add,Edit, Remove, show Detail Discussion of a course*/
    $routes->get('discussion-form/(:num)', 'DiscussionController::addDiscussionForm/$1');
    $routes->post('discussion/(:num)', 'DiscussionController::addDiscussion/$1');
    $routes->get('discussion-form-edit/(:any)', 'DiscussionController::editDiscussionForm/$1');
    $routes->put('discussion-edit/(:any)', 'DiscussionController::editDiscussion/$1');
    $routes->delete('discussion/(:any)', 'DiscussionController::deleteDiscussion/$1');

    // dashboard
    $routes->get('teacher/dashboard', 'DashboardController::teacherDashboard', ['as' => 'teacher_dashboard']);
});


/* Courses Routes for Teacher and Student */
$routes->group('courses', ['filter' => 'role:teacher,student'], ['namespace' => 'App\Controllers'], function ($routes) {
    /* Assignments */
    $routes->group('assignments', function ($routes) {
        $routes->get('show/(:num)', 'AssignmentController::showAssignment/$1', ['as' => 'show_assignment']);
        $routes->get('create/(:num)', 'AssignmentController::createAssignment/$1', ['as' => 'create_assignment']);
        $routes->post('store/(:num)', 'AssignmentController::storeAssignment/$1', ['as' => 'store_assignment']);
        $routes->get('edit/(:num)', 'AssignmentController::editAssignment/$1', ['as' => 'edit_assignment']);
        $routes->put('update/(:num)', 'AssignmentController::updateAssignment/$1', ['as' => 'update_assignment']);
        $routes->delete('delete/(:num)', 'AssignmentController::deleteAssignment/$1', ['as' => 'delete_assignment']);
        $routes->get('file/(:num)/(:segment)', 'AssignmentController::file/$1/$2', ['as' => 'file_assignment']);
        $routes->post('submit/(:num)', 'AssignmentController::submitAssignment/$1', ['as' => 'submit_assignment']);
        $routes->get('submissionFile/(:num)/(:segment)', 'AssignmentController::submissionFile/$1/$2', ['as' => 'submission_file']);
        $routes->delete('deleteSubmission/(:num)', 'AssignmentController::deleteSubmission/$1', ['as' => 'delete_submission']);
    });
});


$routes->group('', ['filter' => 'role:teacher,student'], ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('discussion/(:any)', 'DiscussionController::showDiscussionDetail/$1');
    /* Add Discussion comment of a course*/
    $routes->post('discussion-comment/(:num)', 'DiscussionUserController::addCommentDiscussion/$1');
    $routes->delete('discussion-comment/(:any)', 'DiscussionUserController::deleteCommentDiscussion/$1');
    $routes->put('discussion-comment/(:any)', 'DiscussionUserController::editCommentDiscussion/$1');
});
