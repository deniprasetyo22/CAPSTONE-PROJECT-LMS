<?php

namespace App\Models;

use App\Entities\CourseLecturers;
use CodeIgniter\Model;

class LevelCourseModel extends Model
{
    protected $table            = 'courses_lecturers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = CourseLecturers::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id',
        'lecturer_id',
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
        'course_id'  => 'required',
        'lecturer_id' => 'required',
    ];
    protected $validationMessages   = [
        'course_id' => [
            'required' => 'Course ID is required',
        ],
        'lecturer_id' => [
            'required' => 'Lecturer ID is required',
        ]
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
