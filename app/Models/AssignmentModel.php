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

    public function getAllCountTeacherAssignments($lecturerId)
    {
        return $this->select('COUNT(assignments.id) as total_assignments')
            ->join('courses', 'assignments.course_id = courses.id')
            ->join('courses_lecturers', 'courses_lecturers.course_id = courses.id')
            ->where('courses_lecturers.lecturer_id', $lecturerId)
            ->where('courses.deleted_at', null)
            ->where('assignments.deleted_at', null)
            ->get()
            ->getRowArray();
    }

    public function getLecturerAssignmentSubmissionStats($lecturerId)
    {
        return $this->from('assignments a')
            ->select("
        a.id AS assignment_id,
        COUNT(CASE WHEN s.created_at IS NOT NULL AND s.created_at <= a.due_date THEN 1 END) AS on_time_submissions,
        COUNT(CASE WHEN s.created_at IS NOT NULL AND s.created_at > a.due_date THEN 1 END) AS late_submissions,
        COUNT(e.student_id) - COUNT(s.student_id) AS not_submitted,
        COUNT(e.student_id) AS total_enrollments
    ", false)
            ->join('courses c', 'a.course_id = c.id')
            ->join('courses_lecturers cl', 'cl.course_id = c.id')
            ->join('enrollments e', 'e.course_id = c.id')
            ->join('assignment_submissions s', 's.assignment_id = a.id AND s.student_id = e.student_id', 'left')
            ->where('cl.lecturer_id', $lecturerId)
            ->where('c.deleted_at', null)
            ->where('a.deleted_at', null)
            ->groupBy('a.id')
            ->get()
            ->getResultArray();
    }
}
