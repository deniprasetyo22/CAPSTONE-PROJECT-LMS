<?php

namespace App\Models;

use App\Entities\CourseContent;
use CodeIgniter\Model;

class CourseContentModel extends Model
{
    protected $table            = 'course_contents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = CourseContent::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'course_id', 'title', 'content_type', 'content_url'];

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
        'course_id' => 'required',
        'title' => 'required',
        'content_type' => 'required',
        'content_url' => 'required',
    ];
    protected $validationMessages   = [
        'course_id' => [
            'required' => 'Course ID is required.',
        ],
        'title' => [
            'required' => 'Title is required.',
        ],
        'content_type' => [
            'required' => 'Content Type is required.',
        ],
        'content_url' => [
            'required' => 'Content URL is required.',
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