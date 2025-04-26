<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Files extends Entity
{
    protected $attributes = [
        'id' => null,
        'content_id' => null,
        'file_url' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    protected $casts = [
        'id' => 'integer',
        'content_id' => 'integer',
        'file_url' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}
