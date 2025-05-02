<?php

namespace App\Models;

use App\Entities\Enrollments;
use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Enrollments::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'student_id', 'course_id', 'status', 'progress_percentage', 'grade', 'deleted_at'];

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
        'student_id'     => 'required',
        'course_id'   => 'required',
    ];
    protected $validationMessages   = [
        'student_id'     => [
            'required' => 'User ID is required.',
        ],
        'course_id'   => [
            'required' => 'Course ID is required.',
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

    public function getAllStudentsDashboard()
    {
        $builder = $this->select('COUNT(DISTINCT student_id) AS total_unique_students')->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }

    public function getAllEnrollmentsDashboard()
    {
        $builder = $this->selectCount('id', 'total_enrollments')->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }

    public function getTotalEnrollmentsPerMonth($teacherId = null)
    {
        if ($teacherId) {
            return $this->select("EXTRACT(MONTH FROM enrollments.created_at) AS month, COUNT(enrollments.id) AS total_enrollments")
                ->join('courses', 'enrollments.course_id = courses.id')
                ->join('course_teachers', 'course_teachers.course_id = courses.id')
                ->where('course_teachers.teacher_id', $teacherId)
                ->where('courses.deleted_at', null)
                ->where('course_teachers.deleted_at', null)
                ->where("EXTRACT(YEAR FROM enrollments.created_at)", date('Y'))
                ->groupBy("EXTRACT(MONTH FROM enrollments.created_at)")
                ->orderBy("EXTRACT(MONTH FROM enrollments.created_at)")
                ->findAll();
        }
        return $this->select("EXTRACT(MONTH FROM created_at) AS month, COUNT(id) AS total_enrollments")
            ->where("EXTRACT(YEAR FROM created_at)", date('Y'))
            ->groupBy("EXTRACT(MONTH FROM created_at)")
            ->orderBy("EXTRACT(MONTH FROM created_at)")
            ->findAll();
    }
}