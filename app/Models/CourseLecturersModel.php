<?php

namespace App\Models;

use App\Entities\CourseLecturers;
use CodeIgniter\Model;

class CourseLecturersModel extends Model
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
        'deleted_at',
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

    public function getAllCourseTeachersDashboard()
    {
        $builder = $this->select('COUNT(DISTINCT lecturer_id) AS total_unique_teachers')->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }

    public function getAllCountTeacherCourses($lecturerId)
    {
        $result = $this->join('courses', 'courses.id = courses_lecturers.course_id')
            ->where('courses_lecturers.lecturer_id', $lecturerId)
            ->where('courses.deleted_at', null)
            ->where('courses_lecturers.deleted_at', null)
            ->countAllResults();

        return $result;
    }
}
