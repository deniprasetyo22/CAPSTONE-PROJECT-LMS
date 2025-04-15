<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthGroupSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'         => 1,
                'name' => 'administrator',
                'description' => 'Role Administrator'
            ],
            [
                'id'         => 2,
                'name' => 'teacher',
                'description' => 'Role Teacher'
            ],
            [
                'id'         => 3,
                'name' => 'student',
                'description' => 'Role Student'
            ]
        ];
        
        $this->db->table('auth_groups')->insertBatch($data);
    }
}