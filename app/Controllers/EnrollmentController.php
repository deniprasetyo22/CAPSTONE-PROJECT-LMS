<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserProfileModel;
use CodeIgniter\HTTP\ResponseInterface;

class EnrollmentController extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $userProfileModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->userProfileModel = new UserProfileModel();
    }

    public function store($courseId)
    {
        $enrollmentCode = $this->request->getPost('enrollment_code');

        $currentUserId = user_id();

        $studentId = $this->userProfileModel->where('user_id', $currentUserId)->first()->id;

        if (empty($enrollmentCode)) {
            return redirect()->back()->with('error', 'Enrollment code is required.');
        }

        $course = $this->courseModel->find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        if ($course->enrollment_code !== $enrollmentCode) {
            return redirect()->back()->with('error', 'Invalid enrollment code.');
        }

        // Cek apakah user sudah pernah enroll
        $existingEnrollment = $this->enrollmentModel
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        $enrollmentData = [
            'student_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'enrolled',
            'progress_percentage' => 0,
            'grade' => 0
        ];

        if (!$this->enrollmentModel->save($enrollmentData)) {
            return redirect()->back()->with('error', 'Failed to enroll in the course.');
        }

        return redirect()->back()->with('success', 'You have successfully enrolled in the course.');
    }

    public function leaveCourse($id)
    {
        $enrollment = $this->enrollmentModel->find($id);

        if (!$enrollment) {
            return redirect()->back()->with('error', 'Enrollment not found.');
        }

        if(!$this->enrollmentModel->delete($id)){
            return redirect()->back()->with('error', 'Failed to leave the course.');
        }

        return redirect()->to('/student/courses/my-courses')->with('success', 'You have successfully left the course.');
    }

    public function addNewStudent($courseId)
    {
        $studentId = $this->request->getPost('user_id');


        // Cek apakah user sudah pernah enroll
        $existingEnrollment = $this->enrollmentModel
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('deleted_at', null)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'This student is already enrolled in this course.');
        }

        $enrollmentData = [
            'student_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'enrolled',
            'progress_percentage' => 0,
            'grade' => 0
        ];

        if (!$this->enrollmentModel->save($enrollmentData)) {
            return redirect()->back()->with('error', 'Failed to enroll the student in the course.');
        }

        return redirect()->back()->with('success', 'The student has been successfully enrolled in the course.');
    }
    
    public function removeStudent($id)
    {
        $enrollment = $this->enrollmentModel->find($id);
        if (!$enrollment) {
            return redirect()->back()->with('error', 'Enrollment not found!');
        }

        if ($this->enrollmentModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')])) {
            return redirect()->back()->with('success', 'Enrollment deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete enrollment!');
        }
    }
}