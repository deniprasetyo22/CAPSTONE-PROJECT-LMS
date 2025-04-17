<?php

namespace Myth\Auth\Models;

use App\Libraries\DataParams;
use CodeIgniter\Model;
use Faker\Generator;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Entities\User;

/**
 * @method User|null first()
 */
class UserModel extends Model
{
    protected $table          = 'users';
    protected $primaryKey     = 'id';
    protected $returnType     = User::class;
    protected $useSoftDeletes = true;
    protected $allowedFields  = [
        'email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'permissions', 'deleted_at',
    ];
    protected $useTimestamps   = true;
    protected $validationRules = [
        'id'            => 'is_natural_no_zero|permit_empty',
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username'      => 'required|alpha_numeric_punct|min_length[3]|max_length[30]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required',
    ];
    protected $validationMessages = [
        'email'         => [
            'is_unique' => 'This email address is already exist.',
        ],
        'username'      => [
            'is_unique' => 'This username is already exist.',
        ],
    ];
    protected $skipValidation     = false;
    protected $afterInsert        = ['addToGroup'];

    /**
     * The id of a group to assign.
     * Set internally by withGroup.
     *
     * @var int|null
     */
    protected $assignGroup;

    /**
     * Logs a password reset attempt for posterity sake.
     */
    public function logResetAttempt(string $email, ?string $token = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->db->table('auth_reset_attempts')->insert([
            'email'      => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Logs an activation attempt for posterity sake.
     */
    public function logActivationAttempt(?string $token = null, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->db->table('auth_activation_attempts')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token'      => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Sets the group to assign any users created.
     *
     * @return $this
     */
    public function withGroup(string $groupName)
    {
        $group = $this->db->table('auth_groups')->where('name', $groupName)->get()->getFirstRow();

        $this->assignGroup = $group->id;

        return $this;
    }

    /**
     * Clears the group to assign to newly created users.
     *
     * @return $this
     */
    public function clearGroup()
    {
        $this->assignGroup = null;

        return $this;
    }

    /**
     * If a default role is assigned in Config\Auth, will
     * add this user to that group. Will do nothing
     * if the group cannot be found.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    protected function addToGroup($data)
    {
        if (is_numeric($this->assignGroup)) {
            $groupModel = model(GroupModel::class);
            $groupModel->addUserToGroup($data['id'], $this->assignGroup);
        }

        return $data;
    }

    /**
     * Faked data for Fabricator.
     */
    public function fake(Generator &$faker): User
    {
        return new User([
            'email'    => $faker->email,
            'username' => $faker->userName,
            'password' => bin2hex(random_bytes(16)),
        ]);
    }

    /* Get All User with User Profile */
    public function getAllUserWithProfile()
    {
        return $this->select('users.*, user_profiles.first_name as first_name, user_profiles.last_name as last_name, user_profiles.phone as phone, user_profiles.sex as sex, user_profiles.dob as dob, user_profiles.address as address, user_profiles.profile_picture as profile_picture')
            ->join('user_profiles', 'user_profiles.user_id = users.id')
            ->orderBy('users.id', 'desc');
    }

    public function getFileredUsers(DataParams $params)
    {
        $query = $this->getAllUserWithProfile();

        //Search
        if (!empty($params->search)) {
            $query->groupStart()
            ->where('CAST(users.id as TEXT) LIKE', "%$params->search%")
            ->orLike('user_profiles.first_name', $params->search, 'both', null, true)
            ->orLike('user_profiles.last_name', $params->search, 'both', null, true)
            ->orLike('users.username', $params->search, 'both', null, true)
            ->orLike('users.email', $params->search, 'both', null, true)
            ->groupEnd();
        }

        //Sorting
        $allowedSortColumns = ['id', 'username', 'email', 'first_name', 'last_name', 'sex', 'dob'];
        $sort = in_array($params->sort, $allowedSortColumns) ? $params->sort : 'id';
        $order = ($params->order === 'desc') ? 'desc' : 'asc';

        $this->orderBy($sort, $order);

        $results = [
            'users' => $this->paginate($params->perPage ?? 5, 'users', $params->page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false)
        ];

        return $results;
    }
}