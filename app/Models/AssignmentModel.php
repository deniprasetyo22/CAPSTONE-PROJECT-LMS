<?php

namespace App\Models;

use App\Entities\Assignment;
use CodeIgniter\Model;

class AssignmentModel extends Model
{
    protected $table            = 'assignments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Assignment::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'title', 'description', 'course_id', 'due_date', 'file_url'];

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
        'title' => 'required',
        'description' => 'required',
        'course_id' => 'required',
        'due_date' => 'required',
    ];
    protected $validationMessages   = [
        'title' => [
            'required' => 'Title is required',
            'is_unique' => 'Title is already exists'
        ],
        'description' => [
            'required' => 'Description is required',
        ],
        'course_id' => [
            'required' => 'Course id is required',
        ],
        'due_date' => [
            'required' => 'Due date is required',
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