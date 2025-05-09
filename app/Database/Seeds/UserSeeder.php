<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Password;

class UserSeeder extends Seeder
{
    public function run()
    {   
        $this->db->table('users')->insert([
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@yopmail.com',
            'password_hash' => Password::hash('admin'),
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->table('auth_groups_users')->insert([
            'group_id' => 1,
            'user_id'  => 2
        ]);

        $this->db->table('auth_users_permissions')->insert([
            'user_id' => 1,
            'permission_id' => 1
        ]);
    }
}