<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AssignmentModel;
use App\Models\CourseModel;
use App\Models\CourseTeacherModel;
use App\Models\DiscussionModel;
use App\Models\EnrollmentModel;
use App\Models\UserProfileModel;

class DashboardController extends BaseController
{
    private $enrollmentModel;
    private $courseModel;
    protected $courseTeacherModel;
    protected $userProfileModel;
    protected $discussionModel;
    protected $assignmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->courseTeacherModel = new CourseTeacherModel();
        $this->userProfileModel = new UserProfileModel();
        $this->discussionModel = new DiscussionModel();
        $this->assignmentModel = new AssignmentModel();
    }
    public function adminDashboard()
    {
        $countStudents = $this->enrollmentModel->getAllStudentsDashboard();
        $countCourses =  $this->courseModel->getAllCoursesDashboard();
        $countTeachers = $this->courseTeacherModel->getAllCourseTeachersDashboard();
        $countEnrollments = $this->enrollmentModel->getAllEnrollmentsDashboard();

        // Create Data for Pie Chart
        $totalStudents = $countStudents->total_unique_students;
        $totalTeachers = $countTeachers->total_unique_teachers;
        $totalUsers = $totalStudents + $totalTeachers;

        $gradeLabels[] = 'Students' . ' = ' . $totalStudents . ' users';
        $colors = [
            'rgb(255, 0, 0)',
            'rgb(0, 0, 255)'
        ];
        $gradeLabels[] = 'Teachers' . ' = ' . $totalTeachers . ' users';
        $usersPercentage[] = round((float)($totalStudents / $totalUsers) * 100, 2);
        $usersPercentage[] = round((float)($totalTeachers / $totalUsers) * 100, 2);

        $usersByRole = $this->createPieChart($gradeLabels, $colors, $usersPercentage);

        // Create Data for Bar Chart
        $labels[] = 'Total Enrollments';
        $totalAcademicRecords[] = (int)$countEnrollments->total_enrollments;
        $labels[] = 'Total Courses';
        $totalAcademicRecords[] = (int)$countCourses->total_courses;
        $labels[] = 'Total Students';
        $totalAcademicRecords[] = (int)$countStudents->total_unique_students;
        $labels[] = 'Total Teachers';
        $totalAcademicRecords[] = (int)$countTeachers->total_unique_teachers;

        $totalAcademicRecords = $this->createBarChart($labels, $totalAcademicRecords);

        // Create Data for Line Chart
        $enrollmentGrowthMonth = $this->enrollmentModel->getTotalEnrollmentsPerMonth();
        $listMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        foreach ($listMonths as $index => $month) {
            $isFound = false;
            foreach ($enrollmentGrowthMonth as $row) {
                if ($index === (int) $row->month - 1) {
                    $total_enrollments[] = (int)$row->total_enrollments;
                    $labelMonths[] = $month;
                    $isFound = true;
                    break;
                }
            }
            if (!$isFound) {
                $total_enrollments[] = 0;
                $labelMonths[] = $month;
            }
        }
        $enrollmentGrowthMonth = $this->createLineChart($labelMonths, $total_enrollments);

        $data = [
            'page_title' => 'Admin Dashboard',
            'total_students' => $countStudents->total_unique_students,
            'total_courses' => $countCourses->total_courses,
            'total_teachers' => $countTeachers->total_unique_teachers,
            'total_enrollments' => $countEnrollments->total_enrollments,
            'users_by_role' => json_encode($usersByRole),
            'total_academic_records' => json_encode($totalAcademicRecords),
            'enrollment_growth_month' => json_encode($enrollmentGrowthMonth),
            'hideHeader' => true
        ];

        return view('pages/admin/dashboard/v_admin_dashboard', $data);
    }

    public function teacherDashboard()
    {
        $currentUser = $this->userProfileModel->select('id')->where('user_id', user_id())->first();

        $courseLecturers = $this->courseTeacherModel->getAllCountTeacherCourses($currentUser->id);
        $discussions = $this->discussionModel->getAllCountTeacherDiscussions($currentUser->id);
        $assignments = $this->assignmentModel->getAllCountTeacherAssignments($currentUser->id);

        // Create Data for Bar Chart
        $labels[] = 'Total Courses';
        $totalAcademicTeacherCourses[] = (int)$courseLecturers;
        $labels[] = 'Total Assignments';
        $totalAcademicTeacherCourses[] = (int)$assignments['total_assignments'];
        $labels[] = 'Total Discussions';
        $totalAcademicTeacherCourses[] = (int)$discussions;

        $totalAcademicTeacherCourses = $this->createBarChart($labels, $totalAcademicTeacherCourses);

        // Create Data for Line Chart
        $enrollmentGrowthMonth = $this->enrollmentModel->getTotalEnrollmentsPerMonth($currentUser->id);
        $listMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        foreach ($listMonths as $index => $month) {
            $isFound = false;
            foreach ($enrollmentGrowthMonth as $row) {
                if ($index === (int) $row->month - 1) {
                    $total_enrollments[] = (int)$row->total_enrollments;
                    $labelMonths[] = $month;
                    $isFound = true;
                    break;
                }
            }
            if (!$isFound) {
                $total_enrollments[] = 0;
                $labelMonths[] = $month;
            }
        }
        $enrollmentGrowthMonth = $this->createLineChart($labelMonths, $total_enrollments);

        // Create Data for Pie Chart
        $assignmentStatusesCount = $this->assignmentModel->getTeacherAssignmentSubmissionStats($currentUser->id);
        $totalExpected = 0;
        $totalOnTime = 0;
        $totalLate = 0;
        $totalNotSubmitted = 0;

        foreach ($assignmentStatusesCount as $row) {
            $totalExpected += $row['total_enrollments'];
            $totalOnTime += $row['on_time_submissions'];
            $totalLate += $row['late_submissions'];
            $totalNotSubmitted += $row['not_submitted'];
        }

        if ($totalExpected == 0) {
            $totalExpected = 1;
        }

        $gradeLabels[] = 'Assignment Submitted' . ' = ' . $totalOnTime;
        $gradeLabels[] = 'Assignment Late' . ' = ' . $totalLate;
        $gradeLabels[] = 'Assignment Not Submitted' . ' = ' . $totalNotSubmitted;
        $colors = [
            'rgb(0, 255, 0)',
            'rgb(255, 0, 0)',
            'rgb(255, 255, 0)'

        ];
        $assignmentPercentage[] = round((float)($totalOnTime / $totalExpected) * 100, 2);
        $assignmentPercentage[] = round((float)($totalLate / $totalExpected) * 100, 2);
        $assignmentPercentage[] = round((float)($totalNotSubmitted / $totalExpected) * 100, 2);

        $assignmentByStatus = $this->createPieChart($gradeLabels, $colors, $assignmentPercentage);

        $data = [
            'page_title' => 'Teacher Dashboard',
            'total_courses' => $courseLecturers,
            'total_assignments' => $assignments['total_assignments'],
            'total_discussions' => $discussions,
            'total_academic_teacher_courses' => json_encode($totalAcademicTeacherCourses),
            'enrollment_growth_month' => json_encode($enrollmentGrowthMonth),
            'assignment_by_status' => json_encode($assignmentByStatus),
            'hideHeader' => true
        ];
        return view('pages/teacher/dashboard/teacher_dashboard', $data);
    }

    private function createPieChart($labels, $colors, $percentage)
    {
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'User by Role %',
                    'data' => $percentage,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4
                ]
            ]
        ];
    }

    private function createBarChart($labels, $dataset)
    {
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Academic Records',
                    'data' => $dataset,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
            ]
        ];
    }

    private function createLineChart($label, $dataset)
    {
        return [
            'labels' => $label,
            'datasets' => [
                [
                    'label' => 'Total Enrollments',
                    'data' => $dataset,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'tension' => 0.1,
                    'fill' => false
                ]
            ]
        ];
    }
}