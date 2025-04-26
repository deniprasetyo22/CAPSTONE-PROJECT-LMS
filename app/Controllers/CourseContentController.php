<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseContentModel;
use App\Models\CourseModel;
use App\Models\FileModel;

class CourseContentController extends BaseController
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

    public function addContentForm($courseId)
    {
        helper('form');
        // Check if the course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }
        return view('pages/teacher/course_content/add_course_content', ['course_id' => $courseId]);
    }

    public function addContent($courseId)
    {
        helper('form');
        $userfile = $this->request->getFile('userfile');

        $course = $this->courseModel->where('id', $courseId)->first();
        $courseName = $course ? $course->name : null;
        $uploadPath = WRITEPATH . 'uploads/' . $courseName . '_' . $courseId;
        $nameFile = $userfile->getName();

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $userfile->move($uploadPath, $nameFile);
        $filepath = $uploadPath . '/' . $nameFile;
        $filePathRelative = str_replace(WRITEPATH, '', $filepath);

        $contentData = [
            'course_id' => $courseId,
            'title' => $this->request->getPost('title'),
            'content_type' => 'Material'
        ];

        if (!$this->courseContentModel->save($contentData)) {
            return redirect()->back()->with('error', 'Failed to add content to the course.');
        }

        $contentId = $this->courseContentModel->insertID();
        $fileData = [
            'content_id' => $contentId,
            'file_url' => $filePathRelative
        ];
        if (!$this->fileModel->save($fileData)) {
            return redirect()->back()->with('error', 'Failed to add file to the course content.');
        }

        return redirect()->to('/courses/detail/' . $courseId)->with('success', 'Course content added successfully!');
    }

    public function showFileContent($encodedPath)
    {
        $filename = $this->base64url_decode($encodedPath); // decode dulu path-nya
        $filePath = WRITEPATH . $filename;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return $this->response
            ->setContentType(mime_content_type($filePath))
            ->setBody(file_get_contents($filePath));
    }

    public function showContent($id, $contentId)
    {
        $course = $this->courseModel->find($id);
        if (!$course) {
            return redirect()->to('/courses/my-courses')->with('error', 'Course not found!');
        }

        $courseContent = $this->courseContentModel
            ->where('id', $contentId)
            ->where('course_id', $id) // pastikan content milik course yang dimaksud
            ->first();
        // dd($courseContent);

        if (!$courseContent) {
            return redirect()->to('/courses/my-courses')->with('error', 'Course content not found!');
        }

        $file = $this->fileModel->where('content_id', $courseContent->id)->orderBy('id', 'desc')->findAll();

        $fileWithNames = array_map(function ($f) {
            $f->fileName = basename($f->file_url);
            $f->encodedUrl = $this->base64url_encode($f->file_url);
            return $f;
        }, $file);

        $data = [
            'page_title' => $course->name . ' - ' . $courseContent->title,
            'course' => $course,
            'courseContent' => $courseContent,
            'files' => $fileWithNames
        ];

        return view('pages/teacher/course_content/detail_show_content', $data);
    }

    public function addFileContentForm($courseContentId)
    {
        helper('form');
        // Check if the course exists
        $courseContent = $this->courseContentModel->find($courseContentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }
        return view('pages/teacher/file/add_file', ['course_content_id' => $courseContentId]);
    }

    public function addFileContent($courseContentId)
    {
        helper('form');
        $userfile = $this->request->getFile('userfile');

        $courseContent = $this->courseContentModel
            ->select('course_contents.id, courses.name, courses.id as "course_id"')
            ->join('courses', 'courses.id = course_contents.course_id')
            ->where('course_contents.id', $courseContentId)->first();

        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }

        $uploadPath = WRITEPATH . 'uploads/' . $courseContent->name . '_' . $courseContent->course_id;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $nameFile = $userfile->getName();

        $userfile->move($uploadPath);
        $filepath = $uploadPath . '/' . $nameFile;
        $filePathRelative = str_replace(WRITEPATH, '', $filepath);

        // Save the file information to the database
        $fileData = [
            'content_id' => $courseContentId,
            'file_url' => $filePathRelative
        ];

        if (!$this->fileModel->save($fileData)) {
            return redirect()->back()->with('error', 'Failed to add file to the course content.');
        }

        return redirect()->to('course-content/' . $courseContent->course_id . '/' . $courseContentId)->with('success', 'File added successfully!');
    }

    public function deleteFileContent($fileId)
    {
        $file = $this->fileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $filePath = WRITEPATH . $file->file_url;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if (!$this->fileModel->delete($fileId)) {
            return redirect()->back()->with('error', 'Failed to delete file!');
        }

        return redirect()->back()->with('success', 'File deleted successfully!');
    }

    public function editFileContentForm($fileId)
    {
        helper('form');
        $file = $this->fileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $file->encodedUrl = $this->base64url_encode($file->file_url);

        return view('pages/teacher/file/edit_file', [
            'file_id' => $fileId,
            'file' => $file
        ]);
    }

    public function editFileContent($fileId)
    {
        $file = $this->fileModel->find($fileId);
        if (!$file) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $filePath = WRITEPATH . $file->file_url;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $userfile = $this->request->getFile('userfile');
        $nameFile = $userfile->getName();
        $userfile->move(dirname($filePath), $nameFile);

        $filepath = dirname($filePath) . '/' . $nameFile;
        $filePathRelative = str_replace(WRITEPATH, '', $filepath);

        // Update the file information in the database
        $data = [
            'file_url' => $filePathRelative
        ];

        if (!$this->fileModel->update($fileId, $data)) {
            return redirect()->back()->with('error', 'Failed to update file!');
        }

        return redirect()->to('/courses/detail/' . $this->request->getPost('course_id'))->with('success', 'File updated successfully!');
    }

    public function editContentForm($contentId)
    {
        $courseContent = $this->courseContentModel->find($contentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }

        return view('pages/teacher/course_content/edit_course_content', [
            'content_id' => $contentId,
            'course_content' => $courseContent
        ]);
    }

    public function editContent($contentId)
    {
        $courseContent = $this->courseContentModel->find($contentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }

        $data = [
            'title' => $this->request->getPost('title'),
        ];

        $courseContent->fill($data);

        if (!$this->courseContentModel->save($courseContent)) {
            return redirect()->back()->with('error', 'Failed to update course content.');
        }

        return redirect()->to('/courses/detail/' . $courseContent->course_id)->with('success', 'Course content updated successfully!');
    }

    public function deleteContent($contentId)
    {
        $courseContent = $this->courseContentModel->find($contentId);
        if (!$courseContent) {
            return redirect()->back()->with('error', 'Course content not found.');
        }

        if (!$this->courseContentModel->delete($contentId)) {
            return redirect()->back()->with('error', 'Failed to delete course content.');
        }

        return redirect()->to('/courses/detail/' . $courseContent->course_id)->with('success', 'Course content deleted successfully!');
    }
}
