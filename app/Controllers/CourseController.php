<?php

namespace App\Controllers;


use App\Libraries\DataParams;
use App\Models\AssignmentModel;
use App\Models\CourseContentModel;
use App\Models\CourseModel;
use App\Models\CourseTeacherModel;
use App\Models\DiscussionModel;
use App\Models\EnrollmentModel;
use App\Models\FileModel;
use App\Models\LevelCourseModel;
use App\Models\UserProfileModel;
use CodeIgniter\Files\File;

class CourseController extends BaseController
{
    protected $courseModel;
    protected $levelCourseModel;
    protected $courseTeacherModel;
    protected $userProfileModel;
    protected $enrollmentModel;
    protected $courseContentModel;
    protected $fileModel;
    protected $discussionModel;
    protected $assignmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->levelCourseModel = new LevelCourseModel();
        $this->courseTeacherModel = new CourseTeacherModel();
        $this->userProfileModel = new UserProfileModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseContentModel = new CourseContentModel();
        $this->fileModel = new FileModel();
        $this->discussionModel = new DiscussionModel();
        $this->assignmentModel = new AssignmentModel();
    }

    public function index(): string
    {
        $listCourses = $this->courseModel->select('courses.*, level_courses.name as levelName')->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('courses.deleted_at', null)->findAll();
        return view('pages/admin/courses/index', [
            'courses' => $listCourses,
        ]);
    }

    public function listCoursesAdmin(): string
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'page' => $this->request->getGet('page_courses'),
            'perPage' => $this->request->getGet('perPage')
        ]);

        $result = $this->courseModel->getFilteredCourses($params);

        $data = [
            'courses' => $result['courses'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'page_title' => 'List of all courses',
            'hideHeader' => true
        ];

        return view('pages/admin/courses/list_courses', $data);
    }

    /* Course for Student and Teacher */
    public function courseList()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_courses'),
            'perPage' => $this->request->getGet('perPage'),
            'level' => $this->request->getGet('level'),
        ]);

        $courses = $this->courseModel->getFilteredCourses($params);

        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $enrollments = $this->enrollmentModel->where('student_id', $currentUser->id)->findAll();
        $enrolledCourseIds = array_column($enrollments, 'course_id');

        // Batasi jumlah kata pada deskripsi
        foreach ($courses['courses'] as $course) {
            $course->description = $this->limitWords($course->description, 20);
        }

        $data = [
            'page_title' => 'Course List',
            'courses' => $courses['courses'],
            'pager' => $courses['pager'],
            'total' => $courses['total'],
            'params' => $params,
            'studentId' => $currentUser->id,
            'enrolledCourseIds' => $enrolledCourseIds,
            'level' => $this->levelCourseModel->findAll(),
            'baseUrl' => base_url('courses/index'),
        ];

        return view('pages/courses/v_index', $data);
    }

    private function limitWords(string $text, int $limit = 20): string
    {
        $words = explode(' ', strip_tags($text));
        return implode(' ', array_slice($words, 0, $limit)) . (count($words) > $limit ? '...' : '');
    }

    public function studentCourses()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_my_courses'),
            'perPage' => $this->request->getGet('perPage'),
            'level' => $this->request->getGet('level'),
        ]);

        $myCourses = $this->courseModel->getFilteredStudentCourses($params);

        // Batasi jumlah kata pada deskripsi
        foreach ($myCourses['myCourses'] as $myCourse) {
            $myCourse->description = $this->limitWords($myCourse->description, 20);
        }

        $data = [
            'page_title' => 'My Courses',
            'myCourses' => $myCourses['myCourses'],
            'pager' => $myCourses['pager'],
            'total' => $myCourses['total'],
            'params' => $params,
            'level' => $this->levelCourseModel->findAll(),
            'baseUrl' => base_url('courses/student-courses'),
        ];

        return view('pages/courses/students/v_student_courses', $data);
    }

    public function teacherCourses()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_my_courses'),
            'perPage' => $this->request->getGet('perPage'),
            'level' => $this->request->getGet('level'),
        ]);

        $teacherCourses = $this->courseModel->getFilteredTeacherCourses($params);

        // Batasi jumlah kata pada deskripsi
        foreach ($teacherCourses['teacherCourses'] as $teacherCourse) {
            $teacherCourse->description = $this->limitWords($teacherCourse->description, 20);
        }

        $data = [
            'page_title' => 'My Courses',
            'teacherCourses' => $teacherCourses['teacherCourses'],
            'pager' => $teacherCourses['pager'],
            'total' => $teacherCourses['total'],
            'params' => $params,
            'level' => $this->levelCourseModel->findAll(),
            'baseUrl' => base_url('courses/teacher-courses'),
        ];

        return view('pages/courses/teachers/v_teacher_courses', $data);
    }

    public function showCourse($id)
    {
        $studentId = $this->userProfileModel->where('user_id', user_id())->first()->id;
        $enrollment = $this->enrollmentModel->where('student_id', $studentId)->where('course_id', $id)->first();

        $course = $this->courseModel->select('courses.*, level_courses.name as levelName')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')->find($id);

        $courseContents = $this->courseContentModel->where('course_id', $id)->findAll();

        $assignments = $this->assignmentModel->where('course_id', $id)->findAll();

        $teachers = $this->courseTeacherModel->select('user_profiles.*, users.email, course_teachers.id')
            ->join('user_profiles', 'user_profiles.id = course_teachers.teacher_id')
            ->join('users', 'users.id = user_profiles.user_id')
            ->where('course_teachers.course_id', $id)
            ->where('course_teachers.deleted_at', null)
            ->findAll();

        $discussions = $this->discussionModel
            ->where('course_id', $id)
            ->where('deleted_at', null)
            ->orderBy('id', 'desc')
            ->findAll();

        $students = $this->enrollmentModel
            ->select('user_profiles.*, users.email, enrollments.id')
            ->join('user_profiles', 'user_profiles.id = enrollments.student_id')
            ->join('users', 'users.id = user_profiles.user_id')
            ->where('enrollments.course_id', $id)
            ->where('enrollments.deleted_at', null)
            ->findAll();

        $data = [
            'page_title' => $course->name,
            'course' => $course,
            'courseContents' => $courseContents,
            'enrollment' => $enrollment,
            'assignments' => $assignments,
            'discussions' => $discussions,
            'teachers' => $teachers,
            'students' => $students
        ];

        return view('pages/courses/v_show_course', $data);
    }

    public function file($filename)
    {
        $filePath = WRITEPATH . 'uploads/files/' . $filename;
        // dd($filePath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        // return $this->response->download($filePath, null);
        return $this->response->setContentType(mime_content_type($filePath))->setBody(file_get_contents($filePath));
    }

    public function createCourse(): string
    {
        $levelCourses = $this->levelCourseModel->findAll();
        return view('pages/courses/v_create_course', [
            'levelCourses' => $levelCourses,
            'hideHeader' => true
        ]);
    }

    public function storeCourse()
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();

        $enrollmentCodeInput = $this->request->getPost('enrollment_code');
        
        $data = [
            'code'              => $this->request->getPost('code'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'enrollment_code'   => !empty($enrollmentCodeInput) ? $enrollmentCodeInput : generate_enrollment_code(),
            'expected_duration' => $this->request->getPost('expected_duration'),
            'level_course_id'   => $this->request->getPost('level_course_id'),
        ];

        if ($this->courseModel->save($data)) {
            // Save the course ID to the course_lecturers table
            $courseId = $this->courseModel->insertID();
            $courseTeachersData = [
                'course_id' => $courseId,
                'teacher_id' => $currentUser->id,
            ];
            $this->courseTeacherModel->save($courseTeachersData);
            return redirect()->to(route_to('teacher_courses'))->with('success', 'Course added successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->courseModel->errors());
        }
    }

    public function editCourse($id)
    {
        $course = $this->courseModel->find($id);
        $levelCourses = $this->levelCourseModel->findAll();
        if (!$course) {
            return redirect()->to(route_to('course_list'))->with('error', 'Course not found!');
        }

        return view('pages/courses/v_edit_course', [
            'course' => $course,
            'levelCourses' => $levelCourses,
        ]);
    }

    public function updateCourse($id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to(route_to('course_list'))->with('error', 'Course not found!');
        }

        $data = [
            'code'              => $this->request->getPost('code'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'enrollment_code'   => $this->request->getPost('enrollment_code'),
            'expected_duration' => +$this->request->getPost('expected_duration'),
            'level_course_id'   => +$this->request->getPost('level_course_id'),
        ];

        $isChanged = 0;

        foreach ($data as $key => $value) {
            if ($value === $course->$key) {
                $isChanged++;
            }
        }

        if ($isChanged === count($data)) {
            return redirect()->back()->withInput()->with('errors', ['No changes made to the course!']);
        }

        $course->fill($data);

        if ($this->courseModel->save($course)) {
            return redirect()->to(route_to('teacher_courses'))->with('success', 'Course updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->courseModel->errors());
        }
    }

    public function deleteCourse($id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to(route_to('teacher_courses'))->with('error', 'Course not found!');
        }
        
        $this->courseModel->update($id, [
            'code' => $course->code . '_deletedAt_' . date('Y-m-d_H:i:s'),
            'name' => $course->name . '_deletedAt_' . date('Y-m-d_H:i:s'),
            'enrollment_code' => $course->enrollment_code . '_deletedAt_' . date('Y-m-d_H:i:s')
        ]);
        
        $this->courseModel->delete($id);
        return redirect()->to(route_to('teacher_courses'))->with('success', 'Course deleted successfully!');
    }
}