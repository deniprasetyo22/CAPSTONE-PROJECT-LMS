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
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'name',
        'description',
        'enrollment_code',
        'expected_duration',
        'level_course_id',
        'deleted_at'
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
            ->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('courses.deleted_at', null);
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

        if (!empty($params->level)) {
            $query->where('courses.level_course_id', $params->level);
        } 

        $query->orderBy($params->sort ?? 'id', $params->order ?? 'desc');

        $result = [
            'courses' => $query->paginate($params->perPage ?? 4, 'courses', $params->page),
            'pager' => $query->pager,
            'total' => $query->countAllResults(false)
        ];
        return $result;
    }

    public function getStudentCourses()
    {
        $studentId = $this->db->table('user_profiles')
            ->select('id')
            ->where('user_id', user_id())
            ->get()
            ->getRow()
            ->id ?? null;

        return $this->select('courses.*, level_courses.name as levelName')
            ->join('level_courses', 'level_courses.id = courses.level_course_id')
            ->where('courses.deleted_at', null)
            ->where('enrollments.deleted_at', null)
            ->join('enrollments', 'enrollments.course_id = courses.id', 'left')
            ->where('enrollments.student_id', $studentId);
    }

    public function getFilteredStudentCourses(DataParams $params)
    {
        $query = $this->getStudentCourses();

        if (!empty($params->search)) {
            $query->groupStart()
                ->where('CAST(courses.expected_duration as TEXT) LIKE', "%$params->search%")
                ->orLike('courses.name', $params->search, 'both', null, true)
                ->orLike('courses.code', $params->search, 'both', null, true)
                ->groupEnd();
        }

        if (!empty($params->level)) {
            $query->where('courses.level_course_id', $params->level);
        }

        $query->orderBy($params->sort ?? 'id', $params->order ?? 'desc');

        return [
            'myCourses' => $query->paginate($params->perPage ?? 4, 'myCourses', $params->page),
            'pager' => $query->pager,
            'total' => $query->countAllResults(false)
        ];
    }

    public function getTeacherCourses()
    {
        $teacherId = $this->db->table('user_profiles')
            ->select('id')
            ->where('user_id', user_id())
            ->get()
            ->getRow()
            ->id ?? null;

        return $this->select('courses.*, level_courses.name as levelName, user_profiles.first_name, user_profiles.last_name')
            ->join('level_courses', 'level_courses.id = courses.level_course_id', 'left')
            ->join('course_teachers', 'course_teachers.course_id = courses.id', 'left')
            ->join('user_profiles', 'user_profiles.id = course_teachers.teacher_id', 'left')
            ->where('user_profiles.id', $teacherId);
    }

    public function getFilteredTeacherCourses(DataParams $params)
    {
        $query = $this->getTeacherCourses();

        if (!empty($params->search)) {
            $query->groupStart()
                ->where('CAST(courses.expected_duration as TEXT) LIKE', "%$params->search%")
                ->orLike('courses.name', $params->search, 'both', null, true)
                ->orLike('courses.code', $params->search, 'both', null, true)
                ->groupEnd();
        }

        if (!empty($params->level)) {
            $query->where('courses.level_course_id', $params->level);
        }

        $query->orderBy($params->sort ?? 'id', $params->order ?? 'desc');

        return [
            'teacherCourses' => $query->paginate($params->perPage ?? 4, 'teacherCourses', $params->page),
            'pager' => $query->pager,
            'total' => $query->countAllResults(false)
        ];
    }

    public function getAllCourseWithTeacherStudentLevel($courseLevel = null)
    {
        $builder = $this->db->table('courses')
            ->select("
                courses.id,
                courses.name,
                level_courses.name AS levelName,
                STRING_AGG(DISTINCT teacher.first_name || ' ' || teacher.last_name, ', ') AS teachers,
                STRING_AGG(DISTINCT student.first_name || ' ' || student.last_name, ', ') AS students
            ")
            ->join('level_courses', 'level_courses.id = courses.level_course_id', 'left')
            ->join('course_teachers', 'course_teachers.course_id = courses.id', 'left')
            ->join('user_profiles AS teacher', 'teacher.id = course_teachers.teacher_id', 'left')
            ->join('enrollments', 'enrollments.course_id = courses.id', 'left')
            ->join('user_profiles AS student', 'student.id = enrollments.student_id', 'left')
            ->groupBy('courses.id, courses.name, level_courses.name')
            ->where('courses.deleted_at', null);

        // Menambahkan filter berdasarkan course level jika ada
        if ($courseLevel) {
            $builder->where('courses.level_course_id', $courseLevel);
        }

        return $builder->get()->getResult();
    }


    public function getAllCoursesDashboard()
    {
        $builder = $this->selectCount('id', 'total_courses')
            ->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getRow();
        return $result;
    }
}