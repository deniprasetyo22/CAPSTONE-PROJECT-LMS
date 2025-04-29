<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseLecturersModel;
use App\Models\UserProfileModel;

class CourseLecturerController extends BaseController
{
    protected $courseLecturersModel;
    protected $userProfileModel;

    public function __construct()
    {
        $this->courseLecturersModel = new CourseLecturersModel();
        $this->userProfileModel = new UserProfileModel();
    }

    public function addNewLecturer($courseId)
    {
        $lecturerId = $this->request->getPost('user_lecturer_id');

        // Cek apakah user sudah pernah enroll
        $existingCourseLecturer = $this->courseLecturersModel
            ->where('lecturer_id', $lecturerId)
            ->where('course_id', $courseId)
            ->where('deleted_at', null)
            ->first();

        if ($existingCourseLecturer) {
            return redirect()->back()->with('error', 'This lecturer is already enrolled in this course.');
        }

        $courseLecturerData = [
            'lecturer_id' => $lecturerId,
            'course_id' => $courseId
        ];

        if (!$this->courseLecturersModel->save($courseLecturerData)) {
            return redirect()->back()->with('error', 'Failed to enroll the lecturer in the course.');
        }

        return redirect()->back()->with('success', 'The lecturer has been successfully added in the course.');
    }

    public function removeLecturer($id)
    {
        $courseLecturer = $this->courseLecturersModel->find($id);

        if (!$courseLecturer) {
            return redirect()->back()->with('error', 'This lecturer is not enrolled in this course.');
        }

        if (!$this->courseLecturersModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')])) {
            return redirect()->back()->with('error', 'Failed to remove the lecturer from the course.');
        }

        return redirect()->back()->with('success', 'The lecturer has been successfully removed from the course.');
    }

    /* Course for Lecturer */
    public function lecturerCourseList()
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();
        $courseLecturers = $this->courseLecturersModel
            ->select('courses_lecturers.*, courses.name, courses.code, courses.description, courses.expected_duration, level_courses.name as levelName')
            ->join('courses', 'courses.id = courses_lecturers.course_id')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('courses_lecturers.lecturer_id', $currentUser->id)
            ->where('courses.deleted_at', null)
            ->where('courses_lecturers.deleted_at', null)
            ->findAll();
        $enrolledCourseIds = array_column($courseLecturers, 'course_id');

        $data = [
            'page_title' => 'Course List',
            'myCourses' => $courseLecturers,
            'enrolledCourseIds' => $enrolledCourseIds,
            'hideHeader' => true
        ];

        return view('pages/teacher/course/list_course', $data);
    }

    /* Course for Lecturer */
    public function lecturerCourseListArchived()
    {
        $currentUser = $this->userProfileModel->where('user_id', user_id())->first();

        $builderSub = $this->courseLecturersModel->builder();
        $subQuery = $builderSub
            ->select('MIN(id) as id')
            ->where('deleted_at IS NOT NULL')
            ->groupBy('course_id')
            ->getCompiledSelect();


        $courseLecturers = $this->courseLecturersModel
            ->select('courses_lecturers.*, courses.name, courses.code, courses.description, courses.expected_duration, level_courses.name as levelName')
            ->join('courses', 'courses.id = courses_lecturers.course_id')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('courses.deleted_at IS NOT NULL')
            ->where('courses_lecturers.lecturer_id', $currentUser->id)
            ->where("courses_lecturers.id IN ($subQuery)", null, false)
            ->get()
            ->getResult();

        $enrolledCourseIds = array_column($courseLecturers, 'course_id');

        $data = [
            'page_title' => 'Course List',
            'myCourses' => $courseLecturers,
            'enrolledCourseIds' => $enrolledCourseIds
        ];

        return view('pages/teacher/course/list_course', $data);
    }
}
