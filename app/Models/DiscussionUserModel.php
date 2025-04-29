<?php

namespace App\Models;

use App\Entities\DiscussionUser;
use CodeIgniter\Model;

class DiscussionUserModel extends Model
{
    protected $table            = 'discussion_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = DiscussionUser::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'content',
        'discussion_id',
        'user_profile_id',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'content'           => 'required',
        'discussion_id'     => 'required',
        'user_profile_id'   => 'required',
    ];
    protected $validationMessages   = [
        'content' => [
            'required' => 'Discussion content is required',
        ],
        'discussion_id' => [
            'required' => 'Discussion ID is required',
        ],
        'user_profile_id' => [
            'required' => 'User profile ID is required',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
