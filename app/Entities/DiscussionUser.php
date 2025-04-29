<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DiscussionUser extends Entity
{
    protected $attributes = [
        'id'                => null,
        'content'           => null,
        'discussion_id'     => null,
        'user_profile_id'   => null,
        'created_at'        => null,
        'updated_at'        => null,
        'deleted_at'        => null,
    ];
    protected $casts = [
        'id'                => 'int',
        'content'           => 'string',
        'discussion_id'     => 'int',
        'user_profile_id'   => 'int',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => '?datetime',
    ];
}
