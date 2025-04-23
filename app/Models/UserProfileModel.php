<?php

namespace App\Models;

use App\Entities\UserProfile;
use App\Libraries\DataParams;
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

    public function getJoinedTableUserProfiles()
    {
        return $this->select('user_profiles.first_name, user_profiles.last_name, users.email as email, user_profiles.id as id')
            ->join('users', 'users.id = user_profiles.user_id')
            ->where('user_profiles.deleted_at', null);
    }

    public function getFilteredUserProfiles(DataParams $params)
    {
        $query = $this->getJoinedTableUserProfiles();

        //Search
        if (!empty($params->search)) {
            $query->groupStart()
                ->orLike('user_profiles.first_name', $params->search, 'both', null, true)
                ->orLike('user_profiles.last_name', $params->search, 'both', null, true)
                ->orLike('users.email', $params->search, 'both', null, true)
                ->groupEnd();
        }

        //Sorting
        $sort = 'user_profiles.id';
        $order = 'asc';

        $query->orderBy($sort, $order);

        return [
            'user_profiles' => $query->findAll()
        ];
    }
}
