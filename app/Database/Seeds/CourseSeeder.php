<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'                => 1,
                'code'              => 'CS101',
                'name'              => 'Computer Science Basics',
                'description'       => 'Introduction to basic concepts of computer science including algorithms, data structures, and programming.',
                'enrollment_code'   => 'ENR-2025-001',
                'expected_duration' => 6,
                'level_course_id'   => 1,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'code'              => 'ENG202',
                'name'              => 'Intermediate English',
                'description'       => 'A course designed to improve grammar, vocabulary, and conversational skills in English.',
                'enrollment_code'   => 'ENR-2025-002',
                'expected_duration' => 4,
                'level_course_id'   => 2,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 3,
                'code'              => 'MATH301',
                'name'              => 'Advanced Mathematics',
                'description'       => 'Covers calculus, linear algebra, and differential equations for advanced learners.',
                'enrollment_code'   => 'ENR-2025-003',
                'expected_duration' => 8,
                'level_course_id'   => 3,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'deleted_at'        => null,
            ],
        ];

        // Insert multiple categories
        $this->db->table('courses')->insertBatch($data);
    }
}
