<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Course extends Entity
{
    protected $attributes = [
        'id'                => null,
        'code'              => null,
        'name'              => null,
        'description'       => null,
        'enrollment_code'   => null,
        'expected_duration' => null,
        'level_course_id'   => null,
        'created_at'        => null,
        'updated_at'        => null,
        'deleted_at'        => null,
    ];
    protected $casts = [
        'id'                => 'int',
        'code'              => 'string',
        'name'              => 'string',
        'description'       => 'string',
        'enrollment_code'   => 'string',
        'expected_duration' => 'int',
        'level_course_id'   => 'int',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => '?datetime',
    ];
}
