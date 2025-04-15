<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $getAllUserWithProfile = $this->userModel->getAllUserWithProfile();

        $data = [
            'page_title' => 'Users',
            'users' => $getAllUserWithProfile,
            'hideHeader' => true
        ];
        
        return view('pages/admin/users/v_index', $data);
    }
}