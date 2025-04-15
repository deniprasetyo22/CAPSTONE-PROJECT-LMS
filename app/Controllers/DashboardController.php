<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function adminDashboard()
    {
        $data = [
            'page_title' => 'Admin Dashboard',
            'hideHeader' => true
        ];

        return view('pages/admin/dashboard/v_admin_dashboard', $data);
    }
}