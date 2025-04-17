<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CourseLecturers extends Entity
{
    protected $attributes = [
        'id'         => null,
        'course_id'  => null,
        'lecturer_id' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $casts = [
        'id'         => 'int',
        'course_id'  => 'int',
        'lecturer_id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => '?datetime',
    ];
}
