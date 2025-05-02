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
    protected $allowedFields    = ['id', 'course_id', 'title', 'content_type'];

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

    public function getCourseContentWithFiles($id, $fileName)
    {
        return $this->select('course_contents.*, courses.name as course_name, files.file_url as file_url, files.file_name as file_name')
            ->join('courses', 'courses.id = course_contents.course_id')
            ->join('files', 'files.content_id = course_contents.id')
            ->where('course_contents.id', $id)
            ->where('files.file_url', $fileName)
            ->first();
    }

    public function getAllCourseContentWithFiles()
    {
        return $this->select('course_contents.*, courses.name as course_name, files.file_url as file_url, files.file_name as file_name')
            ->join('courses', 'courses.id = course_contents.course_id', 'left')
            ->join('files', 'files.content_id = course_contents.id', 'left');
    }
}