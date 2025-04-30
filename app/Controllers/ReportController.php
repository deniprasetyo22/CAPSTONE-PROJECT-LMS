<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\LevelCourseModel;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;
use TCPDF;

class ReportController extends BaseController
{
    protected $userModel;
    protected $courseModel;
    protected $groupModel;
    protected $levelModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->groupModel = new GroupModel();
        $this->levelModel = new LevelCourseModel();
    }
    
    public function index()
    {
        $userCategories = $this->groupModel->findAll();

        $courses = $this->courseModel->getAllCourseWithTeacherStudentLevel();

        $courseLevels = $this->levelModel->findAll();

        $data = [
            'page_title' => 'Report',
            'userCategories' => $userCategories,
            'courses' => $courses,
            'courseLevels' => $courseLevels,
            'hideHeader' => true
        ];

        return view('pages/admin/reports/v_index', $data);
    }

    public function exportUsersPDF()
    {
        $userCategory = $this->request->getPost('userCategory');
        
        if ($userCategory) {
            $users = $this->userModel->getAllUserWithProfile()->where('auth_groups.id', $userCategory)->findAll();
        } else {
            $users = $this->userModel->getAllUserWithProfile()->findAll();
        }

        $pdf = $this->initTcpdf();
        $this->generateTcpdfUserList($pdf, $users, $userCategory);

        $fileName = 'User_list_'.date('Y-m-d').'.pdf';
        $pdf->Output($fileName, 'I');
        exit();
    }

    private function initTcpdf()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Administrator');
        $pdf->SetAuthor('Administrator');
        $pdf->SetTitle('User List PDF');
        $pdf->SetSubject('User List PDF');

        $pdf->SetHeaderData('', '0', 'Learning Management System', '', [0, 0, 0], [0, 0, 0]);
        $pdf->setFooterData([0, 64, 0], [0, 64, 128]);
        $pdf->setHeaderFont(['helvetica', '', 12]);
        $pdf->setFooterFont(['helvetica', '', 8]);

        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        
        $pdf->SetAutoPageBreak(true, 25);
        
        $pdf->SetFont('helvetica', '', 10);
        
        $pdf->AddPage();
        
        return $pdf;
    }

    private function generateTcpdfUserList($pdf, $users, $userCategory = null)
    {
        $categoryTitle = 'Category = All Users';
        if ($userCategory == '1') {
            $categoryTitle = 'Category = Administrator';
        } elseif ($userCategory == '2') {
            $categoryTitle = 'Category = Teacher';
        } elseif ($userCategory == '3') {
            $categoryTitle = 'Category = Student';
        }
    
        $title = 'User List';
    
        $html = '
        <h2 style="text-align:center;">' . $title . '</h2>
        <h4 style="margin-bottom:20px;">' . $categoryTitle . '</h4>
        <table border="1" cellpadding="5" style="width:100%;">
            <thead>
            <tr style="background-color:#CCCCCC; font-weight:bold; text-align:center;">
                <th style="width:4%;">No</th>
                <th style="width:12%;">Username</th>
                <th style="width:18%;">Email</th>
                <th style="width:15%;">Full Name</th>
                <th style="width:11%;">Phone</th>
                <th style="width:15%;">Address</th>
                <th style="width:6%;">Sex</th>
                <th style="width:10%;">DOB</th>
                <th style="width:10%;">Registered</th>
            </tr>
            </thead>
            <tbody>';
        
            $no = 1;
            if (empty($users)) {
                $html .= '
                <tr>
                    <td colspan="9" style="text-align:center;">No user found</td>
                </tr>';
            } else {
                foreach ($users as $user) {
                    $html .= '
                <tr>
                    <td style="width:4%;">' . $no++ . '</td>
                    <td style="width:12%;">' . esc($user->username) . '</td>
                    <td style="width:18%;">' . esc($user->email) . '</td>
                    <td style="width:15%;">' . esc($user->first_name . ' ' . $user->last_name) . '</td>
                    <td style="width:11%;">' . esc($user->phone) . '</td>
                    <td style="width:15%;">' . esc($user->address) . '</td>
                    <td style="width:6%;">' . esc($user->sex) . '</td>
                    <td style="width:10%;">' . esc($user->dob) . '</td>
                    <td style="width:10%;">' . esc($user->created_at) . '</td>
                </tr>';
                    $no++;
                }
            }
            
        $html .= '
            </tbody>
            </table>
            
            <p style="margin-top:30px; text-align:left;">      
                <b> Total User: ' . count($users) . '</b> 
            </p>
    
            <p style="margin-top:30px; text-align:right;">    
                <i>Tanggal Cetak: ' . date('d-m-Y H:i:s') .  '</i><br> 
            </p>';
            $pdf->writeHTML($html, true, false, true, false, '');  
    }


    public function exportCoursesPDF()
    {
        $courseLevel = $this->request->getPost('courseLevel');

        $courses = $this->courseModel->getAllCourseWithTeacherStudentLevel($courseLevel);

        $pdf = $this->initTcpdf();
        $this->generateTcpdfCourseList($pdf, $courses);

        $fileName = 'Course_list_' . date('Y-m-d') . '.pdf';
        $pdf->Output($fileName, 'I');
        exit();
    }


    private function generateTcpdfCourseList($pdf, $courses)
    {
        $title = 'Course List';

        $html = '
        <h2 style="text-align:center;">' . $title . '</h2>
        <table border="1" cellpadding="5" style="width:100%;">
            <thead>
            <tr style="background-color:#CCCCCC; font-weight:bold; text-align:center;">
                <th style="width:5%;">No</th>
                <th style="width:20%;">Course Name</th>
                <th style="width:20%;">Level</th>
                <th style="width:25%;">Teachers</th>
                <th style="width:30%;">Students</th>
            </tr>
            </thead>
            <tbody>';

        $no = 1;
        if (empty($courses)) {
            $html .= '
            <tr>
                <td colspan="5" style="text-align:center;">No course found</td>
            </tr>';
        } else {
            foreach ($courses as $course) {
                $html .= '
            <tr>
                <td style="width:5%;">' . $no++ . '</td>
                <td style="width:20%;">' . esc($course->name) . '</td>
                <td style="width:20%;">' . esc($course->levelName) . '</td>
                <td style="width:25%; text-align:center;">
                    ' . $this->formatList($course->teachers) . '
                </td>
                <td style="width:30%; text-align:center;">
                    ' . $this->formatList($course->students) . '
                </td>
            </tr>';
            }
        }

        $html .= '</tbody>
        </table>

        <p style="margin-top:30px; text-align:left;">      
            <b>Total Courses: ' . count($courses) . '</b> 
        </p>

        <p style="margin-top:30px; text-align:right;">    
            <i>Tanggal Cetak: ' . date('d-m-Y H:i:s') . '</i><br> 
        </p>';

        $pdf->writeHTML($html, true, false, true, false, '');
    }

    private function formatList($string)
    {
        if (empty($string)) {
            return '-';
        }

        $items = explode(',', $string);
        $list = '<ol>';
        foreach ($items as $item) {
            $list .= '<li>' . esc(trim($item)) . '</li>';
        }
        $list .= '</ol>';
        return $list;
    }

}