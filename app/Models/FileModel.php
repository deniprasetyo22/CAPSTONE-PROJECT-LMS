<?php

namespace App\Models;

use App\Entities\Files;
use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table            = 'files';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Files::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'content_id',
        'file_url',
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
        'content_id' => 'required',
        'file_url' => 'required',
    ];
    protected $validationMessages   = [
        'content_id' => [
            'required' => 'Content ID is required.',
        ],
        'file_url' => [
            'required' => 'File URL is required.',
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