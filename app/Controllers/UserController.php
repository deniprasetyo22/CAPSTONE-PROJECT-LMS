<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\CourseTeacherModel;
use App\Models\EnrollmentModel;
use App\Models\UserProfileModel;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;

class UserController extends BaseController
{
    protected $userModel;
    protected $userProfileModel;
    protected $courseTeacherModel;
    protected $groupModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userProfileModel = new UserProfileModel();
        $this->groupModel = new GroupModel();
        $this->courseTeacherModel = new CourseTeacherModel();
        $this->enrollmentModel = new EnrollmentModel();
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

        // dd($results['users']);

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

        if (!$this->userProfileModel->save($userProfileData)) {
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

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //Update User
        $userUpdate = $this->userModel->update($id, $userData);

        //Update User Profile
        $userProfileUpdate = $this->userProfileModel->where('user_id', $id)->set($userProfileData)->update();
        if ($userUpdate && $userProfileUpdate) {
            return redirect()->to('admin/users/index')->with('success', 'User updated successfully.');
        }
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users/index')->with('error', 'User not found.');
        }

        // if(!$this->userModel->update($id, [
        //     'email' => $user->email . '_deletedAt_' . date('Y-m-d H:i:s'),
        //     'username' => $user->username . '_deletedAt_' . date('Y-m-d H:i:s'),
        // ])) {
        //     return redirect()->to('admin/users/index')->with('error', 'Failed to delete user.');
        // }

        $this->userProfileModel->where('user_id', $id)->delete();

        $this->userModel->delete($id);

        return redirect()->to('admin/users/index')->with('success', 'User deleted successfully.');
    }

    public function showStudentLists($courseId, $role)
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
        ]);
        $results = $this->userProfileModel->getFilteredUserProfiles($params, $role);

        $filteredUsers = [];
        // Filter users based on the role and course ID
        if ($role == 'teacher') {
            $includedTeachers = $this->courseTeacherModel->where('course_id', $courseId)->where('deleted_at', null)->findAll();
            $includedTeacherIds = array_column($includedTeachers, 'teacher_id');
            $filteredTeachers = array_filter($results['user_profiles'], function ($user) use ($includedTeacherIds) {
                return !in_array($user->id, $includedTeacherIds);
            });
            $filteredUsers = $filteredTeachers;
        } else if ($role == 'student') {
            $includedStudents = $this->enrollmentModel->where('course_id', $courseId)->where('deleted_at', null)->findAll();
            $includedStudentIds = array_column($includedStudents, 'student_id');
            $filteredStudents = array_filter($results['user_profiles'], function ($user) use ($includedStudentIds) {
                return !in_array($user->id,  $includedStudentIds);
            });
            $filteredUsers = $filteredStudents;
        }
        // REINDEX pakai array_values
        $formattedUsers = array_values(array_map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ];
        }, $filteredUsers));

        return $this->response->setJSON($formattedUsers);
    }

    public function studentProfile()
    {
        $studentId = $this->userModel->where('id', user_id())->first()->id;
        $student = $this->userModel->getAllUserWithProfile()->find($studentId);
        // dd($student);
        $data = [
            'page_title' => 'Proile',
            'student' => $student,
        ];
        // dd($data);
        return view('pages/students/v_student_profile', $data);
    }

    public function viewProfilePicture($studentId, $filename)
    {
        $filePath = WRITEPATH . "uploads/files/profile/{$studentId}/{$filename}";

        if(!file_exists($filePath)) {
            $filePath = FCPATH . 'images/default_profile_picture.png';
        }

        return $this->response
                    ->setContentType(mime_content_type($filePath))
                    ->setBody(file_get_contents($filePath));
    }

    public function editStudentProfile()
    {
        $studentId = user_id();
        $student = $this->userModel->getAllUserWithProfile()->find($studentId);
        $data = [
            'page_title' => 'Edit Profile',
            'student' => $student,
        ];
        return view('pages/students/v_edit_student_profile', $data);
    }

    public function updateStudentProfile()
    {
        $studentId = user_id();
        $student = $this->userModel->getAllUserWithProfile()->find($studentId);

        $userData = [
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
        ];

        $rules = $this->userModel->getValidationRules();
        $messages = $this->userModel->getValidationMessages();
    
        $rules['username'] = "required|min_length[3]|is_unique[users.username,id,{$studentId}]";
        $rules['email'] = "required|valid_email|is_unique[users.email,id,{$studentId}]";
        $rules['password_hash'] = 'permit_empty';

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password_hash'] = Password::hash($password);
        } else {
            $userData['password_hash'] = $student->password_hash;
        }
        
        $userProfileId = $student->user_profile_id;
        
        $userProfileData = [
            'user_id' => $studentId,
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'sex' => $this->request->getPost('sex'),
            'dob' => $this->request->getPost('dob'),
        ];

        $profilePic = $this->request->getFile('profile_picture');
        if ($profilePic && $profilePic->isValid() && !$profilePic->hasMoved()) {
            $newName = $profilePic->getRandomName();
            $folderPath = WRITEPATH . 'uploads/files/profile/' . $studentId;
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $profilePic->move($folderPath, $newName);
            $userProfileData['profile_picture'] = $newName;

            $oldFilePath = $folderPath . '/' . $student->profile_picture;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        if (!$this->userModel->update($studentId, $userData)) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
        }        
        
        if (!$this->userProfileModel->update($userProfileId, $userProfileData)) {
            return redirect()->back()->withInput()->with('error', 'Failed to update profile.');
        }
        
        return redirect()->to(url_to('student_profile'))->with('success', 'Profile updated successfully.');
    }

}