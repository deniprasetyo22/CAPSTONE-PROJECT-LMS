<?php

namespace App\Models;

use App\Entities\AssignmentSubmission;
use CodeIgniter\Model;

class AssignmentSubmissionModel extends Model
{
    protected $table            = 'assignment_submissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = AssignmentSubmission::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'student_id', 'assignment_id', 'file_name'];

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
        'student_id' => 'required|permit_empty',
        'assignment_id' => 'required|permit_empty',
        'file_name' => 'required|permit_empty',
    ];
    protected $validationMessages   = [
        'student_id' => [
            'required' => 'Student ID is required',
        ],
        'assignment_id' => [
            'required' => 'Assignment ID is required',
        ],
        'file_name' => [
            'required' => 'File Name is required',
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

    public function getAllAssignmentSubmissionsWithStudentName($id)
    {
        return $this->select('assignment_submissions.*, user_profiles.first_name, user_profiles.last_name')
            ->join('user_profiles', 'user_profiles.id = assignment_submissions.student_id', 'left')
            ->where('assignment_submissions.assignment_id', $id)
            ->findAll();
    }
}