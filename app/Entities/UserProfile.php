<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UserProfile extends Entity
{
    protected $attributes = [
        'id' => null,
        'user_id' => null,
        'first_name' => null,
        'last_name' => null,
        'phone' => null,
        'sex' => null,
        'address' => null,
        'dob' => null,
        'profile_picture' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'phone' => 'string',
        'sex' => 'string',
        'address' => 'string',
        'dob' => 'date',
        'profile_picture' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}