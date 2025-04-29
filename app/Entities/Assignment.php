<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Assignment extends Entity
{
    protected $attributes = [
        'id' => null,
        'title' => null,
        'description' => null,
        'course_id' => null,
        'due_date' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'course_id' => 'integer',
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}