<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateAssignmentSubmissionsTable extends Migration
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
            'file_path'       => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'submission_date'       => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'feedback'       => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'score'       => [
                'type'       => 'NUMERIC',
                'constraint' => '5,2',
                'null'       => true
            ],
            'student_id'     => [
                'type'       => 'INTEGER',
                'unsigned'   => true,
                'null'       => false,
            ],
            'assignment_id'     => [
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
        $this->forge->addForeignKey('student_id', 'user_profiles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assignment_id', 'assignments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assignment_submissions', true);
    }

    public function down()
    {
        $this->forge->dropTable('assignment_submissions', true);
    }
}
