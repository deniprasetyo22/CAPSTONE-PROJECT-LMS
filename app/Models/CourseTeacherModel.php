<?php

namespace App\Models;

use App\Entities\CourseTeacher;
use CodeIgniter\Model;

class CourseTeacherModel extends Model
{
    protected $table            = 'course_teachers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = CourseTeacher::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id',
        'teacher_id',
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
        'teacher_id' => 'required',
    ];
    protected $validationMessages   = [
        'course_id' => [
            'required' => 'Course ID is required',
        ],
        'teacher_id' => [
            'required' => 'Teacher ID is required',
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
        $builder = $this->select('COUNT(DISTINCT teacher_id) AS total_unique_teachers')->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }

    public function getAllCountTeacherCourses($lecturerId)
    {
        $result = $this->join('courses', 'courses.id = course_teachers.course_id')
            ->where('course_teachers.teacher_id', $lecturerId)
            ->where('courses.deleted_at', null)
            ->where('course_teachers.deleted_at', null)
            ->countAllResults();

        return $result;
    }
}