<?php


namespace App\Repositories\User;

use App\Idea\Base\BasePaging;
use App\Models\Idea\Profile;
use App\Models\Idea\User;
use App\Models\Idea\UserRole;
use Illuminate\Support\Facades\Auth;
use Idea\Helpers\TwitterSDK;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;
use App\Idea\Types\ExceptionType;

/**
 * Description: The following repository is responsible for handling all the business logic revolving around User's Authentication and Account
 * Class UserAccountRepository
 * @package App\Repositories
 */
class UserAccountRepository
{
    use ExceptionType;

    protected $user;
    protected $userRole;
    protected $profile;

    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    /**
     * @param JWTAuth $jwt
     * @param User $user
     * @param UserRole $userRole
     * @param Profile $profile
     */
    public function __construct(JWTAuth $jwt, User $user, UserRole $userRole, Profile $profile)
    {
        $this->user = $user;
        $this->userRole = $userRole;
        $this->profile = $profile;
        $this->jwt = $jwt;
    }

    /**
     * Description: The following method authenticate and return user.
     * @author Hassan Mehmood - I2L
     * @param $username
     * @param $password
     * @return array
     */
    public function userLogin($username, $password)
    {
        // attempt to verify the credentials and create a token for the user
        if (!$token = $this->jwt->attempt(['username' => $username, 'password' => $password])) {
            $this->raiseHttpResponseException('idea::general.invalid_user_name_or_password');
        }

        $this->user = Auth::user();

        //check if user deactivated
        if (!$this->user->active) {
            $this->raiseHttpResponseException('idea::general.your_account_is_still_deactivated');
        }

        if (!$this->user->isCmsUser()) {
            $this->raiseHttpResponseException('idea::general.login_error');
        }

        //update user device token
        $this->user->linkDevice();

        $toReturn = $this->user->returnUser();
        $toReturn['token'] = $this->user->generateJWTToken(1440);//ttl 1 day, 1440 minutes
        return $toReturn;
    }

    /**
     * Description: This function returns all admins
     * @author Hassan Mehmood - I2L
     * @return Query
     */
    public function findAll()
    {
        $query = $this->user::with("roles")->whereHas(
            'roles',
            function ($q) {
                $q->where('slug', "!=", 'external');
            }
        );
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected admin
     * @author Hassan Mehmood - I2L
     * @return User
     */
    public function findOne($id)
    {
        return $this->user::with('roles')->find($id);
    }

    /**
     * Description: The following method will add new admin to the system
     *
     * @param email    : the admin email address
     * @param name     : the admin name
     * @param role_id  : the role_id of the admin
     * @param username : the admin username
     * @param password : the admin password
     *
     * @return success or failure
     */
    public function registerNewAdmin($data = [])
    {
        $user           = $this->user;
        $user->username = (isset($data['username'])) ? $data['username'] : request('username');
        $user->email    = (isset($data['email'])) ? $data['email'] : request('email');
        $user->password = (isset($data['password'])) ? Hash::make($data['password']) : Hash::make(request('password'));
        $user->name     = (isset($data['name'])) ? $data['name'] : request('name');
        $user->active   = (isset($data['active'])) ? $data['active'] : request('active', 0);
        $user->getJWTCustomClaims();

        $role_id        = (isset($data['role_id'])) ? $data['role_id'] : request('role_id');
        $user->assignRolePermission([$role_id]);
        if (! $user->save()) {
            $this->raiseHttpResponseException('idea::general.couldnt_created_new_user_please_try_again_later');
        }

        //creating empty profile
        $profile          = $this->profile;
        $profile->user_id = $user->id;
        $profile->save();

        return $user;
    }

    /**
     * Description: The following method will update admin to the system
     *
     * @param email    : the admin email address
     * @param name     : the admin name
     * @param role_id  : the role_id of the admin
     * @param username : the admin username
     * @param password : the admin password
     *
     * @return success or failure
     */
    public function updateAdmin($id)
    {
        $user = $this->user::find($id);
        if (! $user) {
            $this->raiseHttpResponseException('idea::general.couldnt_update_user_please_try_again_later');
        }

        $user->username = request('username');
        $user->name     = request('name');
        //TODO check if email or username are already set for another user
        $user->email  = request('email');
        $user->active = request('active', 0);
        if ($password = request('password')) {
            $user->password = Hash::make($password);
            $user->getJWTCustomClaims();
        }

        $user->assignRolePermission([request('role_id')]);
        if (! $user->save()) {
            $this->raiseHttpResponseException('idea::general.couldnt_update_user_please_try_again_later');
        }

        return $user;
    }

    /**
     * Description: This function will delete respected admin
     * @author Hassan Mehmood - I2L
     * @return boolean
     */
    public function deleteAdmin($id)
    {
        $user = $this->user::find($id);
        if (! $user || in_array($user->id, [1, 2])) {
            $this->raiseHttpResponseException('cannot_delete_admin');
        }

        $user->username = 'deleted_'.time().'_'.$user->username;
        $user->email = 'deleted_'.time().'_'.$user->email;
        $user->save();

        return ($user->delete()) ? true : false;
    }

    /**
     * Description: This function will return role of respected admin
     * @param $id
     * @return boolean
     * @author Hassan Mehmood - I2L
     */
    public function getRoleByUser($id)
    {
        $logged_in_user_role = $this->userRole->ByUserId($id)->select('slug')->join('roles', 'roles.id', '=', 'user_roles.role_id')->first();
        return (isset($logged_in_user_role->slug)) ? $logged_in_user_role->slug : "external";
    }
}
