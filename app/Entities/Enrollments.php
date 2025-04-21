<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Enrollments extends Entity
{
    protected $attributes = [
        'id' => null,
        'student_id' => null,
        'course_id' => null,
        'status' => null,
        'progress_percentage' => null,
        'grade' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'course_id' => 'integer',
        'status' => 'string',
        'progress_percentage' => 'float',
        'grade' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}