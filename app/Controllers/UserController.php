<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\UserProfileModel;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $userProfileModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userProfileModel = new UserProfileModel();
        $this->groupModel = new GroupModel();
    }
    
    public function index()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_users'),
            'perPage' => $this->request->getGet('perPage'),
        ]);

        $results = $this->userModel->getFileredUsers($params);

        $data = [
            'page_title' => 'Users',
            'users' => $results['users'],
            'pager' => $results['pager'],
            'total' => $results['total'],
            'params' => $params,
            'hideHeader' => true
        ];

        // dd($results['pager']);
        
        return view('pages/admin/users/v_index', $data);
    }

    public function create()
    {
        $data = [
            'page_title' => 'Create User',
            'hideHeader' => true
        ];

        return view('pages/admin/users/v_create', $data);
    }

    public function store()
    {
        // Buat entitas user, password akan otomatis di-hash
        $user = new \Myth\Auth\Entities\User();
        $user->username = $this->request->getVar('username');
        $user->email = $this->request->getVar('email');
        $user->password = $this->request->getVar('password');
        $user->active = 1;

        // Simpan user dan dapatkan ID-nya
        if (!$this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('error', 'Create user failed.');
        }

        $userId = $this->userModel->getInsertID();

        // Ambil data dari form
        $firstName  = $this->request->getPost('fname');
        $lastName   = $this->request->getPost('lname');
        $phone      = $this->request->getPost('phone');
        $address    = $this->request->getPost('address');
        $role       = $this->request->getPost('role');
        $sex        = $this->request->getPost('sex');
        $dob        = $this->request->getPost('dob');
        $profilePicture = '/images/default_profile_picture.png';
        
        // Simpan ke tabel user_profiles
        $userProfileData = [
            'user_id'    => $userId,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'phone'      => $phone,
            'address'    => $address,
            'sex'        => $sex,
            'dob'        => $dob,
            'profile_picture' => $profilePicture
        ];

        if(!$this->userProfileModel->save($userProfileData)) {
            return redirect()->back()->withInput()->with('error', 'Create user failed.');
        }

        // Tambahkan user ke grup
        if ($role === 'teacher') {
            $teacherGroup = $this->groupModel->where('name', 'teacher')->first();
            if (!empty($teacherGroup)) {
                $this->groupModel->addUserToGroup($userId, $teacherGroup->id);
            }
        } elseif ($role === 'student') {
            $studentGroup = $this->groupModel->where('name', 'student')->first();
            if (!empty($studentGroup)) {
                $this->groupModel->addUserToGroup($userId, $studentGroup->id);
            }
        }

        return redirect()->to('admin/users/index')->with('success', 'User created successfully.');
    }


}