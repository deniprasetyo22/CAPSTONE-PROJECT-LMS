<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseContentModel;
use App\Models\CourseModel;
use App\Models\FileModel;

class MaterialController extends BaseController
{
    protected $courseContentModel;
    protected $courseModel;
    private $fileModel;

    public function __construct()
    {
        $this->courseContentModel = new CourseContentModel();
        $this->courseModel = new CourseModel();
        $this->fileModel = new FileModel();
    }

    private function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function showMaterial($id, $contentId)
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

        return view('pages/courses/materials/v_show_material', $data);
    }

    public function createMaterial($courseId)
    {
        helper('form');
        // Check if the course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }
        return view('pages/courses/materials/v_create_material', ['course_id' => $courseId]);
    }

    public function storeMaterial($courseId)
    {
        helper('form');
        $userfile = $this->request->getFile('userfile');

        $course = $this->courseModel->where('id', $courseId)->first();

        $newName = $userfile->getRandomName();
        $filePath = url_title($course->name, '-', true);
        $userfile->move(WRITEPATH . 'uploads/files/materials/'.$filePath, $newName);

        $contentData = [
            'course_id' => $courseId,
            'title' => $this->request->getPost('title'),
            'content_type' => 'Material'
        ];
        if (!$this->courseContentModel->save($contentData)) {
            return redirect()->back()->with('error', 'Failed to add material to the course.');
        }

        $contentId = $this->courseContentModel->insertID();
        $fileData = [
            'content_id' => $contentId,
            'file_name' => $this->request->getPost('title'),
            'file_url' => $newName
        ];
        if (!$this->fileModel->save($fileData)) {
            return redirect()->back()->with('error', 'Failed to add file to the course material.');
        }

        return redirect()->to(route_to('show_course', $courseId))->with('success', 'Course material added successfully!');
    }

    public function editMaterial($contentId)
    {
        $courseContent = $this->courseContentModel->find($contentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course material not found.');
        }

        return view('pages/courses/materials/v_edit_material', [
            'content_id' => $contentId,
            'course_content' => $courseContent
        ]);
    }

    public function updateMaterial($contentId)
    {
        $courseContent = $this->courseContentModel->find($contentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course material not found.');
        }

        $data = [
            'title' => $this->request->getPost('title'),
        ];

        $courseContent->fill($data);

        if (!$this->courseContentModel->save($courseContent)) {
            return redirect()->back()->with('error', 'Failed to update course content.');
        }

        return redirect()->to(route_to('show_course', $courseContent->course_id))->with('success', 'Course material updated successfully!');
    }

    public function deleteMaterial($contentId)
    {
        $courseContent = $this->courseContentModel->find($contentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course material not found.');
        }

        if (!$this->courseContentModel->delete($contentId)) {
            return redirect()->back()->with('error', 'Failed to delete course material.');
        }

        return redirect()->to(route_to('show_course', $courseContent->course_id))->with('success', 'Course material deleted successfully!');
    }

    public function showFileMaterial($id, $fileName)
    {
        $courseContent = $this->courseContentModel->getCourseContentWithFiles($id, $fileName);

        if (!$courseContent) {
            return redirect()->back()->with('error', 'Content not found');
        }

        $folderPath = url_title($courseContent->course_name, '-', true);
        $filePath = WRITEPATH . 'uploads/files/materials/' . $folderPath . '/' . $courseContent->file_url;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return $this->response
            ->setContentType(mime_content_type($filePath))
            ->setBody(file_get_contents($filePath));
    }

    public function addFileMaterial($courseContentId)
    {
        helper('form');
        // Check if the course exists
        $courseContent = $this->courseContentModel->find($courseContentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }
        return view('pages/courses/materials/v_add_file_material', ['courseContent' => $courseContent, 'course_content_id' => $courseContentId]);
    }

    public function storeFileMaterial($courseContentId)
    {
        helper('form');
        $userfile = $this->request->getFile('userfile');

        $courseContent = $this->courseContentModel->getAllCourseContentWithFiles()
            ->where('course_contents.id', $courseContentId)
            ->first();

        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }

        $folderPath = url_title($courseContent->course_name, '-', true);
        $destinationPath = WRITEPATH . 'uploads/files/materials/' . $folderPath;

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $newName = $userfile->getRandomName();

        $userfile->move($destinationPath, $newName);

        $fileData = [
            'content_id' => $courseContentId,
            'file_name' => $courseContent->title,
            'file_url' => $newName
        ];

        if (!$this->fileModel->save($fileData)) {
            return redirect()->back()->with('error', 'Failed to add file to the course content.');
        }

        return redirect()->to(route_to('show_material', $courseContent->course_id, $courseContent->id))
            ->with('success', 'File added successfully!');
    }

    public function editFileMaterial($fileId)
    {
        helper('form');
        $file = $this->fileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $file->encodedUrl = $this->base64url_encode($file->file_url);

        $courseContent = $this->courseContentModel->getAllCourseContentWithFiles()->where('course_contents.id', $file->content_id)->first();

        return view('pages/courses/materials/v_edit_file_material', [
            'file_id' => $fileId,
            'file' => $file,
            'courseContent' => $courseContent
        ]);
    }

    public function updateFileMaterial($fileId)
    {
        $file = $this->fileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $courseContent = $this->courseContentModel
            ->getAllCourseContentWithFiles()
            ->where('course_contents.id', $file->content_id)
            ->first();

        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found!');
        }

        $userfile = $this->request->getFile('userfile');
        if (!$userfile->isValid()) {
            return redirect()->back()->with('error', 'Uploaded file is invalid!');
        }

        $folderPath = url_title($courseContent->course_name, '-', true);
        $destinationPath = WRITEPATH . 'uploads/files/materials/' . $folderPath;

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $oldFilePath = $destinationPath . '/' . $file->file_url;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        $newName = $userfile->getRandomName();
        $userfile->move($destinationPath, $newName);

        $data = [
            'file_url' => $newName
        ];

        if (!$this->fileModel->update($fileId, $data)) {
            return redirect()->back()->with('error', 'Failed to update file!');
        }

        return redirect()->to(route_to('show_material', $courseContent->course_id, $courseContent->id))
            ->with('success', 'File updated successfully!');
    }

    public function deleteFileMaterial($fileId)
    {
        $file = $this->fileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $courseContent = $this->courseContentModel
            ->getAllCourseContentWithFiles()
            ->where('course_contents.id', $file->content_id)
            ->first();

        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found!');
        }

        $folderPath = url_title($courseContent->course_name, '-', true);
        $filePath = WRITEPATH . 'uploads/files/materials/' . $folderPath . '/' . $file->file_url;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if (!$this->fileModel->delete($fileId)) {
            return redirect()->back()->with('error', 'Failed to delete file!');
        }

        return redirect()->to(route_to('show_material', $courseContent->course_id, $courseContent->id))
            ->with('success', 'File deleted successfully!');
    }
    
}