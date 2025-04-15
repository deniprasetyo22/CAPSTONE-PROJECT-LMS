<?php

namespace App\Models;

use App\Entities\UserProfile;
use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table            = 'user_profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = UserProfile::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'user_id', 'first_name', 'last_name', 'phone', 'address', 'dob', 'sex', 'profile_picture'];

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
        'first_name' => 'required',
        'last_name'  => 'required',
        'phone'      => 'required',
        'address'    => 'required',
        'dob'        => 'required',
        'sex'        => 'required',
    ];
    protected $validationMessages   = [
        'first_name' => [
            'required' => 'First Name is required',
        ],
        'last_name' => [
            'required' => 'Last Name is required',
        ],
        'phone' => [
            'required' => 'Phone is required',
        ],
        'address' => [
            'required' => 'Address is required',
        ],
        'dob' => [
            'required' => 'Date of Birth is required',
        ],
        'sex' => [
            'required' => 'Sex is required',
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
}