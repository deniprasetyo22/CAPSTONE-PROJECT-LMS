<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/* Public Routes */
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'AuthController::login');
    $routes->get('login', 'AuthController::login', ['as' => 'login']);
    $routes->post('login', 'AuthController::attemptLogin');
});

/* Admin Routes */
$routes->group('admin', ['filter' => 'role:administrator'], ['namespace' => 'App\Controllers'], function ($routes) {
    /* Dashboard */
    $routes->get('dashboard', 'DashboardController::adminDashboard', ['as' => 'admin_dashboard']);

    /* Report */
    $routes->group('reports', function ($routes) {
        $routes->get('index', 'ReportController::index', ['as' => 'reports']);
        $routes->post('exportUsersPDF', 'ReportController::exportUsersPDF', ['as' => 'export_users_pdf']);
        $routes->post('exportCoursesPDF', 'ReportController::exportCoursesPDF', ['as' => 'export_courses_pdf']);
    });

    /* User */
    $routes->group('users', function ($routes) {
        $routes->get('index', 'UserController::index', ['as' => 'users']);
        $routes->get('show/(:num)', 'UserController::show/$1', ['as' => 'show_user']);
        $routes->get('create', 'UserController::create', ['as' => 'create_user']);
        $routes->post('store', 'UserController::store', ['as' => 'store_user']);
        $routes->get('edit/(:num)', 'UserController::edit/$1', ['as' => 'edit_user']);
        $routes->put('update/(:num)', 'UserController::update/$1', ['as' => 'update_user']);
        $routes->delete('delete/(:num)', 'UserController::delete/$1', ['as' => 'delete_user']);
        $routes->get('view-profile-pic/(:segment)/(:any)', 'UserController::viewProfilePicture/$1/$2', ['as' => 'view_profile_pic']);
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

// $routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
//     $routes->get('/courses', 'CourseController::index', ['as' => 'courses']);
//     $routes->get('/admin/courses', 'CourseController::listCoursesAdmin', ['as' => 'list_courses']);
// });

/* Student Routes */
$routes->group('students', ['filter' => 'role:student'], ['namespace' => 'App\Controllers'], function ($routes) {
    /* Profile */
    $routes->group('profile', function ($routes) {
        $routes->get('index', 'UserController::studentProfile', ['as' => 'student_profile']);
        $routes->get('edit', 'UserController::editStudentProfile', ['as' => 'edit_student_profile']);
        $routes->put('update', 'UserController::updateStudentProfile', ['as' => 'update_student_profile']);
        $routes->get('view-profile-picture/(:segment)/(:any)', 'UserController::viewProfilePicture/$1/$2', ['as' => 'view_profile_picture']);
    });
    
    /* Enrollemnt */
    $routes->group('enrollment', function ($routes) {
        $routes->post('store/(:num)', 'EnrollmentController::store/$1', ['as' => 'store_enrollment']);
        $routes->delete('leave/(:num)', 'EnrollmentController::leaveCourse/$1', ['as' => 'leave_course']);
    });
});

/* Teacher Routes */
$routes->group('teachers', ['filter' => 'role:teacher'], ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('search-users/(:num)', 'UserController::showStudentLists/$1', ['as' => 'search_users']);
    $routes->post('enroll_student/(:num)', 'EnrollmentController::addNewStudent/$1', ['as' => 'enroll_student']);
    $routes->post('add_teacher_course/(:num)', 'CourseTeacherController::addNewTeacher/$1', ['as' => 'add_teacher_course']);
    $routes->delete('remove_teacher_course/(:num)', 'CourseTeacherController::removeTeacher/$1', ['as' => 'remove_teacher_course']);
    $routes->delete('remove_student_course/(:num)', 'EnrollmentController::removeStudent/$1', ['as' => 'remove_student_course']);

    // dashboard
    $routes->get('teacher/dashboard', 'DashboardController::teacherDashboard', ['as' => 'teacher_dashboard']);
});


/* Courses Routes for Teacher and Student */
$routes->group('courses', ['filter' => 'role:teacher,student'], ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('index', 'CourseController::courseList', ['as' => 'course_list']);
    $routes->get('student-courses', 'CourseController::studentCourses', ['as' => 'student_courses']);
    $routes->get('teacher-courses', 'CourseController::teacherCourses', ['as' => 'teacher_courses']);

    $routes->get('show-course/(:num)', 'CourseController::showCourse/$1', ['as' => 'show_course']);
    $routes->get('create-course', 'CourseController::createCourse', ['as' => 'create_course']);
    $routes->post('store-course', 'CourseController::storeCourse', ['as' => 'store_course']);
    $routes->get('edit-course/(:num)', 'CourseController::editCourse/$1', ['as' => 'edit_course']);
    $routes->put('update-course/(:num)', 'CourseController::updateCourse/$1', ['as' => 'update_course']);
    $routes->delete('delete-course/(:num)', 'CourseController::deleteCourse/$1', ['as' => 'delete_course']);
        
        // $routes->get('file/(:segment)', 'CourseController::file/$1', ['as' => 'file']);
    $routes->group('materials', function ($routes) {
        $routes->get('show-material/(:num)/(:num)', 'MaterialController::showMaterial/$1/$2', ['as' => 'show_material']);
        $routes->get('create-material/(:num)', 'MaterialController::createMaterial/$1', ['as' => 'create_material']);
        $routes->post('store-material/(:num)', 'MaterialController::storeMaterial/$1', ['as' => 'store_material']);
        $routes->get('edit-material/(:num)', 'MaterialController::editMaterial/$1', ['as' => 'edit_material']);
        $routes->put('update-material/(:num)', 'MaterialController::updateMaterial/$1', ['as' => 'update_material']);
        $routes->delete('delete-material/(:num)', 'MaterialController::deleteMaterial/$1', ['as' => 'delete_material']);

        $routes->get('show-file-material/(:num)/(:segment)', 'MaterialController::showFileMaterial/$1/$2', ['as' => 'show_file_material']);
        $routes->get('add-file-material/(:num)', 'MaterialController::addFileMaterial/$1', ['as' => 'add_file_material']);
        $routes->post('store-file-material/(:num)', 'MaterialController::storeFileMaterial/$1', ['as' => 'store_file_material']);
        $routes->get('edit-file-material/(:num)', 'MaterialController::editFileMaterial/$1', ['as' => 'edit_file_material']);
        $routes->put('update-file-material/(:num)', 'MaterialController::updateFileMaterial/$1', ['as' => 'update_file_material']);
        $routes->delete('delete-file-material/(:num)', 'MaterialController::deleteFileMaterial/$1', ['as' => 'delete_file_material']);
    });
    
    // $routes->get('detail/(:num)', 'CourseController::detailCourse/$1', ['as' => 'course_detail']);
    // $routes->get('course-teacher-archived', 'CourseTeacherController::teacherCourseListArchived', ['as' => 'archived_teacher_courses']);
    
    /* Assignments */
    $routes->group('assignments', function ($routes) {
        $routes->get('show-assignment/(:num)', 'AssignmentController::showAssignment/$1', ['as' => 'show_assignment']);
        $routes->get('create-assignment/(:num)', 'AssignmentController::createAssignment/$1', ['as' => 'create_assignment']);
        $routes->post('store-assignment/(:num)', 'AssignmentController::storeAssignment/$1', ['as' => 'store_assignment']);
        $routes->get('edit-assignment/(:num)', 'AssignmentController::editAssignment/$1', ['as' => 'edit_assignment']);
        $routes->put('update-assignment/(:num)', 'AssignmentController::updateAssignment/$1', ['as' => 'update_assignment']);
        $routes->delete('delete-assignment/(:num)', 'AssignmentController::deleteAssignment/$1', ['as' => 'delete_assignment']);
        $routes->get('file-assignment/(:num)/(:segment)', 'AssignmentController::file/$1/$2', ['as' => 'file_assignment']);
        $routes->post('submit-assignment/(:num)', 'AssignmentController::submitAssignment/$1', ['as' => 'submit_assignment']);
        $routes->get('submissionFile-assignment/(:num)/(:segment)', 'AssignmentController::submissionFile/$1/$2', ['as' => 'submission_file']);
        $routes->delete('deleteSubmission-assignment/(:num)', 'AssignmentController::deleteSubmission/$1', ['as' => 'delete_submission']);
    });

    /* Discussion */
    $routes->group('discussion', function ($routes) {
        $routes->get('discussion/(:segment)', 'DiscussionController::showDiscussionDetail/$1', ['as' => 'show_discussion']);
        
        /* Add Discussion comment of a course*/
        $routes->post('discussion-comment/(:num)', 'DiscussionUserController::addCommentDiscussion/$1', ['as' => 'add_comment_discussion']);
        $routes->delete('discussion-comment/(:any)', 'DiscussionUserController::deleteCommentDiscussion/$1', ['as' => 'delete_comment_discussion']);
        $routes->put('discussion-comment/(:any)', 'DiscussionUserController::editCommentDiscussion/$1', ['as' => 'update_comment_discussion']);

        /* Add,Edit, Remove, show Detail Discussion of a course*/
        $routes->get('discussion-form/(:num)', 'DiscussionController::addDiscussionForm/$1', ['as' => 'create_discussion']);
        $routes->post('discussion/(:num)', 'DiscussionController::addDiscussion/$1', ['as' => 'store_discussion']);
        $routes->get('discussion-form-edit/(:any)', 'DiscussionController::editDiscussionForm/$1', ['as' => 'edit_discussion']);
        $routes->put('discussion-edit/(:any)', 'DiscussionController::editDiscussion/$1', ['as' => 'update_discussion']);
        $routes->delete('discussion/(:any)', 'DiscussionController::deleteDiscussion/$1', ['as' => 'delete_discussion']);
    });
});