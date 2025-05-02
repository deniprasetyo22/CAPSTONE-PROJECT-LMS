<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateCourseTeacherTable extends Migration
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
            'teacher_id'     => [
                'type'       => 'INTEGER',
                'unsigned'   => true,
                'null'       => false,
            ],
            'course_id'     => [
                'type'       => 'INTEGER',
                'unsigned'   => true,
                'null'       => false,
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
        $this->forge->addForeignKey('teacher_id', 'user_profiles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('course_teachers', true);
    }

    public function down()
    {
        $this->forge->dropTable('course_teachers', true);
    }
}