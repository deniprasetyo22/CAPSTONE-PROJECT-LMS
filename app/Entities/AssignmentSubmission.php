<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AssignmentSubmission extends Entity
{
    protected $attributes = [
        'id' => null,
        'student_id' => null,
        'assignment_id' => null,
        'file_name' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected $casts = [
        'id' => 'integer',
        'student_id' => 'integer',
        'assignment_id' => 'integer',
        'file_name' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}