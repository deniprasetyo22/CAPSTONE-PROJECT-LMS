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
            'baseUrl' => base_url('admin/users/index'),
            'hideHeader' => true
        ];

        // dd($results['pager']);
        
        return view('pages/admin/users/v_index', $data);
    }

    public function show($id)
    {
        $user = $this->userModel->getAllUserWithProfile()->find($id);
        $data = [
            'page_title' => 'User Detail',
            'user' => $user,
            'hideHeader' => true
        ];
        return view('pages/admin/users/v_show', $data);
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

    public function edit($id)
    {
        $user = $this->userModel->getAllUserWithProfile()->find($id);
        $roleId = $this->groupModel->getGroupsForUser($id);
        $roleName = $roleId[0]['name'];
        $data = [
            'page_title' => 'Edit User',
            'user' => $user,
            'role' => $roleName,
            'hideHeader' => true
        ];
        return view('pages/admin/users/v_edit', $data);
    }

    public function update($id)
    {
        // Cek apakah ID valid dan data ada
        $existingUser = $this->userModel->find($id);
        if (!$existingUser) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Ambil data user utama
        $userData = [
            'id'       => $id,
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
        ];

        // Jika password tidak kosong, hash password
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
            unset($userData['password_hash']);
        }

        $userProfileData = [
            'user_id' => $id,
            'first_name' => $this->request->getPost('fname'),
            'last_name' => $this->request->getPost('lname'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'sex' => $this->request->getPost('sex'),
            'dob' => $this->request->getPost('dob'),
        ];

        $rules = $this->userModel->getValidationRules();
        $messages = $this->userModel->getValidationMessages();

        $rules['username'] = "required|min_length[3]|is_unique[users.username,id,{$id}]";
        $rules['email'] = "required|valid_email|is_unique[users.email,id,{$id}]";
        $rules['password'] = 'permit_empty';
        $rules['password_hash'] = 'permit_empty';

        if(!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //Update User
        $userUpdate = $this->userModel->update($id, $userData);

        //Update User Profile
        $userProfileUpdate = $this->userProfileModel->where('user_id', $id)->set($userProfileData)->update();
        if($userUpdate && $userProfileUpdate) {
            return redirect()->to('admin/users/index')->with('success', 'User updated successfully.');
        }
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users/index')->with('error', 'User not found.');
        }

        $this->userProfileModel->where('user_id', $id)->delete();

        $this->userModel->delete($id);

        return redirect()->to('admin/users/index')->with('success', 'User deleted successfully.');
    }

}