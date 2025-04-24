<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateFilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SERIAL',
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'content_id' => [
                'type'     => 'INTEGER',
                'unsigned' => true,
                'null'     => false,
            ],
            'file_url' => [
                'type' => 'TEXT',
                'null' => false,
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

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('content_id', 'course_contents', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('files', true);
    }

    public function down()
    {
        $this->forge->dropTable('files', true);
    }
}