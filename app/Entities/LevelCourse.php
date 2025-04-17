<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class LevelCourse extends Entity
{
    protected $attributes = [
        'id'         => null,
        'name'       => null,
        'description' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $casts = [
        'id'   => 'int',
        'name' => 'string',
        'description' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => '?datetime',
    ];
}
