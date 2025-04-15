<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUserProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'user_id'     => [
                'type'       => 'INTEGER',
                'unsigned'   => true,
                'null'       => false,
            ],
            'first_name'       => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'last_name'        => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'phone'            => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'address'          => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'sex' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'dob'              => [
                'type' => 'DATE',
                'null' => true,
            ],
            'profile_picture'  => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'       => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'null' => false,
            ],
            'updated_at'       => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'null' => false,
            ],
            'deleted_at'       => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_profiles', true); // Create the table with IF NOT EXISTS
    }

    public function down()
    {
        $this->forge->dropTable('user_profiles', true); // Drop the table with IF EXISTS
    }
}
