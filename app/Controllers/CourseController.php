<?php

namespace App\Controllers;


use App\Libraries\DataParams;
use App\Models\AssignmentModel;
use App\Models\CourseContentModel;
use App\Models\CourseLecturersModel;
use App\Models\CourseModel;
use App\Models\DiscussionModel;
use App\Models\EnrollmentModel;
use App\Models\FileModel;
use App\Models\LevelCourseModel;
use App\Models\UserProfileModel;
use CodeIgniter\Files\File;

class CourseController extends BaseController
{
    private CourseModel $courseModel;
    private LevelCourseModel $levelCourseModel;
    private CourseLecturersModel $courseLecturersModel;
    protected UserProfileModel $userProfileModel;
    protected EnrollmentModel $enrollmentModel;
    protected $courseContentModel;
    protected $fileModel;
    protected $discussionModel;
    protected $assignmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->levelCourseModel = new LevelCourseModel();
        $this->courseLecturersModel = new courseLecturersModel();
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

    public function addCourseForm(): string
    {
        $levelCourses = $this->levelCourseModel->findAll();
        return view('pages/admin/courses/add_course', [
            'levelCourses' => $levelCourses,
            'hideHeader' => true
        ]);
    }
    public function addCourse()
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $data = [
            'code'              => $this->request->getPost('code'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'enrollment_code'   => generate_enrollment_code(),
            'expected_duration' => $this->request->getPost('expected_duration'),
            'level_course_id'   => $this->request->getPost('level_course_id'),
        ];

        if ($this->courseModel->save($data)) {
            // Save the course ID to the course_lecturers table
            $courseId = $this->courseModel->insertID();
            $courseLecturersData = [
                'course_id' => $courseId,
                'lecturer_id' => $currentUser->id,
            ];
            $this->courseLecturersModel->save($courseLecturersData);
            return redirect()->to('/course-lecturers')->with('success', 'Course added successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->courseModel->errors());
        }
    }
    public function editCourseForm($id)
    {
        $course = $this->courseModel->find($id);
        $levelCourses = $this->levelCourseModel->findAll();
        if (!$course) {
            return redirect()->to('/courses')->with('error', 'Course not found!');
        }

        return view('pages/admin/courses/edit_course', [
            'course' => $course,
            'levelCourses' => $levelCourses,
        ]);
    }
    public function editCourse($id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/courses')->with('error', 'Course not found!');
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
            return redirect()->to('/course-lecturers')->with('success', 'Course updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->courseModel->errors());
        }
    }
    public function deleteCourse($id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/courses')->with('error', 'Course not found!');
        }

        if ($this->courseModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')])) {
            // Delete course lecturers
            $this->courseLecturersModel
                ->where('course_id', $id)
                ->where('deleted_at', null) // pastikan ini ya
                ->set(['deleted_at' => date('Y-m-d H:i:s')])
                ->update();

            return redirect()->to('/course-lecturers')->with('success', 'Course deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete course!');
        }
    }

    /* Course for Student */
    public function studentCourseList()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_courses'),
            'perPage' => $this->request->getGet('perPage'),
            'level' => $this->request->getGet('level'),
        ]);

        // $courses = $this->courseModel->getJoinedTableCourses()->findAll();
        $courses = $this->courseModel->getFilteredCourses($params);

        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $enrollments = $this->enrollmentModel->where('student_id', $currentUser->id)->findAll();
        $enrolledCourseIds = array_column($enrollments, 'course_id');

        $data = [
            'page_title' => 'Course List',
            'courses' => $courses['courses'],
            'pager' => $courses['pager'],
            'total' => $courses['total'],
            'params' => $params,
            'studentId' => $currentUser->id,
            'enrolledCourseIds' => $enrolledCourseIds,
            'level' => $this->levelCourseModel->findAll(),
            'baseUrl' => base_url('student/courses/index'),
        ];

        return view('pages/student/courses/v_index', $data);
    }

    public function myCourses()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_my_courses'),
            'perPage' => $this->request->getGet('perPage'),
            'level' => $this->request->getGet('level'),
        ]);

        $myCourses = $this->courseModel->getFilteredMyCourses($params);

        $data = [
            'page_title' => 'My Courses',
            'myCourses' => $myCourses['myCourses'],
            'pager' => $myCourses['pager'],
            'total' => $myCourses['total'],
            'params' => $params,
            'level' => $this->levelCourseModel->findAll(),
            'baseUrl' => base_url('student/courses/my-courses'),
        ];

        return view('pages/student/courses/v_my_courses', $data);
    }

    public function show($id)
    {
        $studentId = $this->userProfileModel->where('user_id', user_id())->first()->id;
        $enrollment = $this->enrollmentModel->where('student_id', $studentId)->where('course_id', $id)->first();

        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/courses/my-courses')->with('error', 'Course not found!');
        }

        $courseContents = $this->courseContentModel->where('course_id', $id)->findAll();

        $assignments = $this->assignmentModel->where('course_id', $id)->findAll();
        $discussions = $this->discussionModel
            ->where('course_id', $id)
            ->where('deleted_at', null)
            ->orderBy('id', 'desc')
            ->findAll();

        $data = [
            'page_title' => $course->name,
            'course' => $course,
            'courseContents' => $courseContents,
            'enrollment' => $enrollment,
            'assignments' => $assignments,
            'discussions' => $discussions,
        ];

        return view('pages/student/courses/v_show', $data);
    }

    public function showContent($id, $contentId)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/courses/my-courses')->with('error', 'Course not found!');
        }

        $courseContent = $this->courseContentModel
            ->where('id', $contentId)
            ->where('course_id', $id)
            ->first();

        if (!$courseContent) {
            return redirect()->to('/courses/my-courses')->with('error', 'Course content not found!');
        }

        $files = $this->fileModel->where('content_id', $contentId)->findAll();

        $data = [
            'page_title' => $course->name . ' - ' . $courseContent->title,
            'course' => $course,
            'courseContent' => $courseContent,
            'files' => $files
        ];

        return view('pages/student/courses/v_show_content', $data);
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

    public function detailCourse($id)
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
        ]);

        $users = $this->userProfileModel->getFilteredUserProfiles($params);

        $course = $this->courseModel->select('courses.*, level_courses.name as levelName')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')->find($id);

        if (!$course) {
            return redirect()->to('/courses')->with('error', 'Course not found!');
        }

        $courseContents = $this->courseContentModel->where('course_id', $id)
            ->orderBy('id', 'desc')
            ->findAll();

        $assignments = $this->assignmentModel->where('course_id', $id)->findAll();

        $students = $this->enrollmentModel
            ->select('user_profiles.*, users.email, enrollments.id')
            ->join('user_profiles', 'user_profiles.id = enrollments.student_id')
            ->join('users', 'users.id = user_profiles.user_id')
            ->where('enrollments.course_id', $id)
            ->where('enrollments.deleted_at', null)
            ->findAll();

        $lecturers = $this->courseLecturersModel->select('user_profiles.*, users.email, courses_lecturers.id')
            ->join('user_profiles', 'user_profiles.id = courses_lecturers.lecturer_id')
            ->join('users', 'users.id = user_profiles.user_id')
            ->where('courses_lecturers.course_id', $id)
            ->where('courses_lecturers.deleted_at', null)
            ->findAll();

        $discussions = $this->discussionModel
            ->where('course_id', $id)
            ->where('deleted_at', null)
            ->orderBy('id', 'desc')
            ->findAll();

        return view('pages/admin/courses/detail_course', [
            'course' => $course,
            'courseContents' => $courseContents,
            'students' => $students,
            'lecturers' => $lecturers,
            'discussions' => $discussions,
            'params' => $params,
            'users' => $users['user_profiles'],
            'page_title' => 'Course Detail',
            'assignments' => $assignments,
            'hideHeader' => true
        ]);
    }
}
