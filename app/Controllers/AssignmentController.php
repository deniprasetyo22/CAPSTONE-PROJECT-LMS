<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AssignmentModel;
use App\Models\AssignmentSubmissionModel;
use App\Models\CourseModel;
use App\Models\UserProfileModel;

class AssignmentController extends BaseController
{
    protected $assignmentModel;
    protected $courseModel;
    protected $userProfileModel;
    protected $assignmentSubmissionModel;

    public function __construct()
    {
        $this->assignmentModel = new AssignmentModel();
        $this->courseModel = new CourseModel();
        $this->userProfileModel = new UserProfileModel();
        $this->assignmentSubmissionModel = new AssignmentSubmissionModel();
    }
    

    /* Assignments */
    public function showAssignment($id)
    {
        $assignment = $this->assignmentModel->find($id);
        $course = $this->courseModel->where('id', $assignment->course_id)->first();
        
        $getAllAssignmentSubmissions = $this->assignmentSubmissionModel->getAllAssignmentSubmissionsWithStudentName($id);
        $studentId = null;
        if (in_groups('student')) {
            $studentId = $this->userProfileModel->where('user_id', user_id())->first()->id;
        }
       
        $getAssignmentSubmission = $this->assignmentSubmissionModel->where('assignment_id', $id)->where('student_id', $studentId)->first();
        
        $data = [
            'page_title' => 'Assignment Detail',
            'assignment' => $assignment,
            'course' => $course,
            'getAllAssignmentSubmissions' => $getAllAssignmentSubmissions,
            'getAssignmentSubmission' => $getAssignmentSubmission,
            'hideHeader' => true
        ];

        return view('pages/courses/assignments/v_show_assignment', $data);
    }

    public function createAssignment($id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to(route_to('show_course', $id))->with('error', 'Course not found!');
        }

        $data = [
            'page_title' => 'Create Assignment',
            'course' => $course,
            'hideHeader' => true
        ];

        return view('pages/courses/assignments/v_create_assignment', $data);
    }

    public function storeAssignment($id)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/courses/detail/' . $id)->with('error', 'Course not found!');
        }

        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return redirect()->back()->withInput()->with('error', 'Invalid file upload.');
        }

        if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
            return redirect()->back()->withInput()->with('error', 'File size must be less than 5MB.');
        }

        $newName = $file->getRandomName();
        $filePath = url_title($course->name, '-', true);
        $file->move(WRITEPATH . 'uploads/files/assignments/'.$filePath, $newName);

        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'due_date'    => $this->request->getPost('due_date'),
            'course_id'   => $id,
            'file_url'    => $newName,
        ];

        $rules = $this->assignmentModel->getValidationRules();
        $messages = $this->assignmentModel->getValidationMessages();

        $rules['title'] = 'required|is_unique[assignments.title]';
        $rules['course_id'] = 'permit_empty';

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($this->assignmentModel->save($data)) {
            return redirect()->to(route_to('show_course', $id))->with('success', 'Assignment created successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->assignmentModel->errors());
        }
    }

    public function file($id, $filename)
    {
        $course = $this->courseModel->find($id);
        $courseName = url_title($course->name, '-', true);
        $filePath = WRITEPATH . 'uploads/files/assignments/'. $courseName . '/'. $filename;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        // return $this->response->download($filePath, null);
        return $this->response->setContentType(mime_content_type($filePath))->setBody(file_get_contents($filePath));
    }

    public function editAssignment($id)
    {
        $assignment = $this->assignmentModel->find($id);
        $course = $this->courseModel->where('id', $assignment->course_id)->first();
        
        $data = [
            'page_title' => 'Edit Assignment',
            'assignment' => $assignment,
            'course' => $course,
            'hideHeader' => true
        ];

        return view('pages/courses/assignments/v_edit_assignment', $data);
    }

    public function updateAssignment($id)
    {
        $assignment = $this->assignmentModel->find($id);

        if(!$assignment){
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $course = $this->courseModel->find($assignment->course_id);
        if (!$course) {
            return redirect()->to(route_to('show_course', $assignment->course_id))->with('error', 'Course not found.');
        }

        $file = $this->request->getFile('file');
        $data = [
            'id'          => $id,
            'course_id'   => $assignment->course_id,
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'due_date'    => $this->request->getPost('due_date'),
        ];

        $rules = $this->assignmentModel->getValidationRules();
        $messages = $this->assignmentModel->getValidationMessages();

        $rules['title'] = "required|is_unique[assignments.title,id,{$id}]";
        $rules['course_id'] = 'permit_empty';

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {
            if ($file->getSize() > 5 * 1024 * 1024) { // 5MB limit
                return redirect()->back()->withInput()->with('error', 'File size must be less than 5MB.');
            }
    
            $newName = $file->getRandomName();
            $courseName = url_title($course->name, '-', true);
            $uploadPath = WRITEPATH . 'uploads/files/assignments/' . $courseName;
    
            // Buat folder jika belum ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            $file->move($uploadPath, $newName);
    
            // Hapus file lama jika ada
            $oldFilePath = $uploadPath . '/' . $assignment->file_url;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
    
            $data['file_url'] = $newName;
        }
    
        if ($this->assignmentModel->save($data)) {
            return redirect()->to(route_to('show_course', $assignment->course_id))->with('success', 'Assignment updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->assignmentModel->errors());
        }
    }

    public function deleteAssignment($id)
    {
        $assignment = $this->assignmentModel->find($id);

        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $course = $this->courseModel->find($assignment->course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Path file yang mau dihapus
        $courseName = url_title($course->name, '-', true);
        $filePath = WRITEPATH . 'uploads/files/assignments/' . $courseName . '/' . $assignment->file_url;

        // Hapus file kalau file-nya memang ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus assignment dari database
        if ($this->assignmentModel->delete($id)) {
            return redirect()->to(route_to('show_course', $course->id))->with('success', 'Assignment deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete assignment.');
        }
    }

    public function submitAssignment($id)
    {
        $studentId = $this->userProfileModel->where('user_id', user_id())->first()->id;

        $assignment = $this->assignmentModel->find($id);

        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $course = $this->courseModel->find($assignment->course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return redirect()->back()->withInput()->with('error', 'Invalid file upload.');
        }

        if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
            return redirect()->back()->withInput()->with('error', 'File size must be less than 5MB.');
        }
        
        $newName = $file->getRandomName();
        $filePath = url_title($course->name, '-', true);
        $file->move(WRITEPATH . 'uploads/files/assignments/submissions/' . $filePath, $newName);

        $data = [
            'student_id'    => $studentId,
            'assignment_id' => $assignment->id,
            'file_name'     => $newName,
        ];
        
        if ($this->assignmentSubmissionModel->save($data)) {row: 
            return redirect()->back()->with('success', 'Assignment submitted successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->assignmentSubmissionModel->errors());
        }
    }

    public function submissionFile($id, $filename)
    {
        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $course = $this->courseModel->find($assignment->course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $folderName = url_title($course->name, '-', true);
        $filePath = WRITEPATH . 'uploads/files/assignments/submissions/' . $folderName . '/' . $filename;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return $this->response
            ->setContentType(mime_content_type($filePath))
            ->setBody(file_get_contents($filePath));
    }

    public function deleteSubmission($id)
    {
        $assignmentSubmission = $this->assignmentSubmissionModel->find($id);

        if (!$assignmentSubmission) {
            return redirect()->back()->with('error', 'Assignment submission not found.');
        }

        $assignment = $this->assignmentModel->find($assignmentSubmission->assignment_id);
        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found.');
        }

        $course = $this->courseModel->find($assignment->course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        // Path file yang mau dihapus
        $folderName = url_title($course->name, '-', true);
        $filePath = WRITEPATH . 'uploads/files/assignments/submissions/' . $folderName . '/' . $assignmentSubmission->file_name;

        // Hapus file kalau file-nya memang ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus assignment dari database
        if ($this->assignmentSubmissionModel->delete($id)) {
            return redirect()->back()->with('success', 'Assignment submission deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete assignment submission.');
        }
    }


}