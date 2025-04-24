<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseContentModel;
use App\Models\CourseModel;

class CourseContentController extends BaseController
{
    protected $courseContentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->courseContentModel = new CourseContentModel();
        $this->courseModel = new CourseModel();
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
        $newName = $userfile->getRandomName();

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $userfile->move($uploadPath, $newName);
        $filepath = $uploadPath . '/' . $newName;
        $filePathRelative = str_replace(WRITEPATH, '', $filepath);

        $contentData = [
            'course_id' => $courseId,
            'title' => $this->request->getPost('title'),
            'content_url' => $filePathRelative,
            'content_type' => 'Material'
        ];

        if (!$this->courseContentModel->save($contentData)) {
            return redirect()->back()->with('error', 'Failed to add content to the course.');
        }

        return redirect()->to('/courses/detail/' . $courseId)->with('success', 'Course content added successfully!');
    }

    public function showFileContent($encodedPath)
    {
        $filename = base64_decode($encodedPath); // decode dulu path-nya
        $filePath = WRITEPATH . $filename;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found');
        }

        return $this->response
            ->setContentType(mime_content_type($filePath))
            ->setBody(file_get_contents($filePath));
    }
}
