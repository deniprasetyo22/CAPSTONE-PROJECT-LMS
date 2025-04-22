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
        // Ambil data dari form
        $enrollmentCode = $this->request->getPost('enrollment_code');

        // Ambil ID user yang sedang login (asumsikan sudah login)
        $currentUserId = user_id();

        $studentId = $this->userProfileModel->where('user_id', $currentUserId)->first()->id;

        // Validasi input
        if (empty($enrollmentCode)) {
            return redirect()->back()->with('error', 'Enrollment code is required.');
        }

        // Cari course berdasarkan kode (atau kamu bisa kirim course_id langsung di form)
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

        if(!$this->enrollmentModel->save($enrollmentData)) {
            return redirect()->back()->with('error', 'Failed to enroll in the course.');
        }

        return redirect()->back()->with('success', 'You have successfully enrolled in the course.');
    }

}