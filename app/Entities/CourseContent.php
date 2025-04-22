<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CourseContent extends Entity
{
    protected $attributes = [
        'id' => null,
        'course_id' => null,
        'title' => null,
        'content_type' => null,
        'content_url' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected $casts = [
        'id' => 'integer',
        'course_id' => 'integer',
        'title' => 'string',
        'content_type' => 'string',
        'content_url' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}