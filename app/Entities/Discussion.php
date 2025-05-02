<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Discussion extends Entity
{
    protected $attributes = [
        'id' => null,
        'topic' => null,
        'description' => null,
        'created_by' => null,
        'course_id' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected $casts = [
        'id' => 'integer',
        'topic' => 'string',
        'description' => 'string',
        'course_id' =>  'integer',
        'created_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}