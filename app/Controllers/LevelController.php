<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LevelCourseModel;
use CodeIgniter\HTTP\ResponseInterface;

class LevelController extends BaseController
{
    protected $levelModel;

    public function __construct()
    {
        $this->levelModel = new LevelCourseModel();
    }

    public function index()
    {
        $data = [
            'page_title' => 'Course Level',
            'course_level' => $this->levelModel->findAll(),
            'hideHeader' => true
        ];

        return view('pages/admin/course_level/v_index', $data);
    }

    public function create()
    {
        $data = [
            'page_title' => 'Create Course Level',
            'hideHeader' => true
        ];

        return view('pages/admin/course_level/v_create', $data);
    }

    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $rules = $this->levelModel->getValidationRules();
        $messages = $this->levelModel->getValidationMessages();

        $rules['name'] = "required|is_unique[level_courses.name]";

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->levelModel->save($data);

        return redirect()->to('admin/levels/index')->with('success', 'Course level added successfully!');
    }

    public function edit($id)
    {
        $data = [
            'page_title' => 'Edit Course Level',
            'level' => $this->levelModel->find($id),
            'hideHeader' => true
        ];

        return view('pages/admin/course_level/v_edit', $data);
    }

    public function  update($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $rules = $this->levelModel->getValidationRules();
        $messages = $this->levelModel->getValidationMessages();

        $rules['name'] = "required|is_unique[level_courses.name,id,{$id}]";

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->levelModel->update($id, $data);

        return redirect()->to('admin/levels/index')->with('success', 'Course level updated successfully!');
    }

    public function delete($id)
    {
        $this->levelModel->delete($id);

        return redirect()->to('admin/levels/index')->with('success', 'Course level deleted successfully!');
    }
}