<?php

namespace App\Models;

use App\Entities\Course;
use App\Libraries\DataParams;
use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Course::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'name',
        'description',
        'enrollment_code',
        'expected_duration',
        'level_course_id',
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
        'code'              => 'required|is_unique[courses.code,id,{id}]',
        'name'              => 'required',
        'description'       => 'required',
        'enrollment_code'   => 'required|is_unique[courses.enrollment_code,id,{id}]',
        'expected_duration' => 'required',
        'level_course_id'   => 'required',
    ];
    protected $validationMessages   = [
        'code' => [
            'required' => 'Course code is required',
            'is_unique' => 'Course code already exists',
        ],
        'name' => [
            'required' => 'Course name is required',
        ],
        'description' => [
            'required' => 'Course description is required',
        ],
        'enrollment_code' => [
            'required' => 'Enrollment code is required',
            'is_unique' => 'Enrollment code already exists',
        ],
        'expected_duration' => [
            'required' => 'Expected duration is required',
        ],
        'level_course_id' => [
            'required' => 'Level course is required',
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

    public function getJoinedTableCourses()
    {
        return $this->select('courses.*, level_courses.name as levelName')
            ->join('level_courses', 'level_courses.id = courses.level_course_id');
    }

    public function getFilteredCourses(DataParams $params)
    {
        $query = $this->getJoinedTableCourses();
        if (!empty($params->search)) { // Apply search
            $query->groupStart()
                ->where('CAST(courses.expected_duration as TEXT) LIKE', "%$params->search%")
                ->orLike('courses.name', $params->search, 'both', null, true)
                ->orLike('courses.code', $params->search, 'both', null, true)
                ->orLike('courses.description', $params->search, 'both', null, true)
                ->orLike('level_courses.name', $params->search, 'both', null, true)
                ->groupEnd();
        }

        $result = [
            'courses' => $this->paginate($params->perPage, 'courses', $params->page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false)
        ];
        return $result;
    }
}
