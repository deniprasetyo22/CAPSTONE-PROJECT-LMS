<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthPermissionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'         => 1,
                'name' => 'manage-users',
                'description' => 'Manage All Features'
            ],
            [
                'id'         => 2,
                'name' => 'manage-teacher',
                'description' => "Manage Teacher Features"
            ],
            [
                'id'         => 3,
                'name' => 'manage-student',
                'description' => 'Manage Student Features'
            ]
        ];
        
        $this->db->table('auth_permissions')->insertBatch($data);
    }
}