<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseLecturersModel;

class CourseLecturerController extends BaseController
{
    protected $courseLecturersModel;

    public function __construct()
    {
        $this->courseLecturersModel = new CourseLecturersModel();
    }

    public function addNewLecturer($courseId)
    {
        $lecturerId = $this->request->getPost('user_lecturer_id');

        // Cek apakah user sudah pernah enroll
        $existingCourseLecturer = $this->courseLecturersModel
            ->where('lecturer_id', $lecturerId)
            ->where('course_id', $courseId)
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
}
