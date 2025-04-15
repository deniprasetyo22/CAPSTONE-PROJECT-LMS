<?php

namespace App\Controllers;

use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Controllers\AuthController as MythAuthController;

class AuthController extends MythAuthController
{
    protected $auth;
    protected $groupModel;

    public function __construct()
    {
        parent::__construct();
        $this->auth = service('authentication');

        $this->groupModel = new GroupModel();
    }

    public function login()
    {
        if($this->auth->check()) {
            return redirect()->to('home');
        }

        return parent::login();
    }

    public function attemptLogin()
    {
        parent::attemptLogin();
        return $this->redirectBasedOnRole();
    }

    private function redirectBasedOnRole()
    {
        $userId = user_id();

        if(!$userId) {
            return redirect()->to('login');
        }

        $userGroups = $this->groupModel->getGroupsForUser($userId);
            
        foreach ($userGroups as $group) {
            if ($group['name'] === 'administrator') {
                return redirect()->to('admin/dashboard');
            } else if ($group['name'] === 'teacher') {
                return redirect()->to('teacher/dashboard');
            } else if ($group['name'] === 'student') {
                return redirect()->to('home');
            }
        }
    
        return redirect()->to('/');
    }

}