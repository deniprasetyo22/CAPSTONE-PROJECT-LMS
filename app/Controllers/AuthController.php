<?php

namespace App\Controllers;

use Myth\Auth\Controllers\AuthController as MythAuthController;

class AuthController extends MythAuthController
{
    protected $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = service('authentication');
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

        return redirect()->to('home');
    }

}