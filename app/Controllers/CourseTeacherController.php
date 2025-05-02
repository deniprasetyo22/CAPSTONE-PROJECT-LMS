<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseTeacherModel;
use App\Models\UserProfileModel;

class CourseTeacherController extends BaseController
{
    protected $courseTeacherModel;
    protected $userProfileModel;

    public function __construct()
    {
        $this->courseTeacherModel = new CourseTeacherModel();
        $this->userProfileModel = new UserProfileModel();
    }

    public function addNewTeacher($courseId)
    {
        $teacherId = $this->request->getPost('user_teacher_id');

        // Cek apakah user sudah pernah enroll
        $existingCourseTeacher = $this->courseTeacherModel
            ->where('teacher_id', $teacherId)
            ->where('course_id', $courseId)
            ->where('deleted_at', null)
            ->first();

        if ($existingCourseTeacher) {
            return redirect()->back()->with('error', 'This teacher is already enrolled in this course.');
        }

        $courseTeacherData = [
            'teacher_id' => $teacherId,
            'course_id' => $courseId
        ];

        if (!$this->courseTeacherModel->save($courseTeacherData)) {
            return redirect()->back()->with('error', 'Failed to enroll the teacher in the course.');
        }

        return redirect()->back()->with('success', 'The teacher has been successfully added in the course.');
    }

    public function removeTeacher($id)
    {
        $courseTeacher = $this->courseTeacherModel->find($id);

        if (!$courseTeacher) {
            return redirect()->back()->with('error', 'This teacher is not enrolled in this course.');
        }

        if (!$this->courseTeacherModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')])) {
            return redirect()->back()->with('error', 'Failed to remove the teacher from the course.');
        }

        return redirect()->back()->with('success', 'The teacher has been successfully removed from the course.');
    }

    /* Course for Teacher */
    public function teacherCourseList()
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $courseTeachers = $this->courseTeacherModel
            ->select('course_teachers.*, courses.name, courses.code, courses.description, courses.expected_duration, level_courses.name as levelName')
            ->join('courses', 'courses.id = course_teachers.course_id')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('course_teachers.teacher_id', $currentUser->id)
            ->where('courses.deleted_at', null)
            ->where('course_teachers.deleted_at', null)
            ->findAll();
        $enrolledCourseIds = array_column($courseTeachers, 'course_id');

        $data = [
            'page_title' => 'Course List',
            'myCourses' => $courseTeachers,
            'enrolledCourseIds' => $enrolledCourseIds,
            'hideHeader' => true
        ];

        return view('pages/teacher/course/list_course', $data);
    }

    /* Course for Teacher */
    public function teacherCourseListArchived()
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();

        $builderSub = $this->courseTeacherModel->builder();
        $subQuery = $builderSub
            ->select('MIN(id) as id')
            ->where('deleted_at IS NOT NULL')
            ->groupBy('course_id')
            ->getCompiledSelect();


        $courseTeachers = $this->courseTeacherModel
            ->select('course_teachers.*, courses.name, courses.code, courses.description, courses.expected_duration, level_courses.name as levelName')
            ->join('courses', 'courses.id = course_teachers.course_id')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('courses.deleted_at IS NOT NULL')
            ->where('course_teachers.teacher_id', $currentUser->id)
            ->where("course_teachers.id IN ($subQuery)", null, false)
            ->get()
            ->getResult();

        $enrolledCourseIds = array_column($courseTeachers, 'course_id');

        $data = [
            'page_title' => 'Course List',
            'myCourses' => $courseTeachers,
            'enrolledCourseIds' => $enrolledCourseIds
        ];

        return view('pages/teacher/course/list_course', $data);
    }
}