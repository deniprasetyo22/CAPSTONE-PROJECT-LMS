<?php

namespace App\Models;

use App\Entities\Discussion;
use CodeIgniter\Model;

class DiscussionModel extends Model
{
    protected $table            = 'discussions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Discussion::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'topic',
        'description',
        'course_id',
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
        'topic'              => 'required',
        'description'       => 'required',
        'course_id'         => 'required',
    ];
    protected $validationMessages   = [
        'topic' => [
            'required' => 'Discussion topic is required',
        ],
        'description' => [
            'required' => 'Discussion description is required',
        ],
        'course_id' => [
            'required' => 'Course ID is required',
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
