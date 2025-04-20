<?php

namespace App\Controllers;


use App\Libraries\DataParams;
use App\Models\CourseLecturersModel;
use App\Models\CourseModel;
use App\Models\LevelCourseModel;

class CourseController extends BaseController
{
    private CourseModel $courseModel;
    private LevelCourseModel $levelCourseModel;
    private CourseLecturersModel $courseLecturersModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->levelCourseModel = new LevelCourseModel();
        $this->courseLecturersModel = new courseLecturersModel();
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
        $userId = user_id();
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
                'lecturer_id' => $userId,
            ];
            $this->courseLecturersModel->save($courseLecturersData);
            return redirect()->to('/courses')->with('success', 'Course added successfully!');
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
            return redirect()->to('/courses')->with('success', 'Course updated successfully!');
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
            return redirect()->to('/courses')->with('success', 'Course deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete course!');
        }
    }


    /* Course for Student */
    public function studentCourseList()
    {
        $courses = $this->courseModel->getJoinedTableCourses()->findAll();
        $data = [
            'page_title' => 'Course List',
            'courses' => $courses,
        ];
        return view('pages/student/courses/v_index', $data);
    }
}