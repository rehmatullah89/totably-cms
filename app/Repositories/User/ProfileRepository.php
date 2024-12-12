<?php


namespace App\Repositories\User;

use App\Idea\Base\BasePaging;
use App\Models\Idea\User;
use App\Models\Idea\Profile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Idea\Types\ExceptionType;

/**
 * Description: The following repository is used to handle all function related to user profile etc
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class ProfileRepository
{
    use ExceptionType;

    protected $user;
    protected $profile;

    public function __construct(User $user, Profile $profile)
    {
        $this->user = $user;
        $this->profile = $profile;
    }

    /**
     * Description: This function returns all admins
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        $query = $this->profile::with("user");
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected admin
     * @param $id
     * @return \App\Models\Idea\User
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        return $this->profile::with('user')->find($id);
    }

    /**
     * Description: This function will delete respected profile
     * @param $id
     * @param $imageService
     * @return boolean
     * @author Hassan Mehmood - I2L
     */
    public function deleteProfile($id, $imageService)
    {
        $profile = $this->profile::find($id);
        if (!$profile) {
            $this->raiseHttpResponseException('cannot_delete_profile');
        }

        // delete image first
        $imageService->deleteImage($profile);

        return ($profile->delete()) ? true : false;
    }

    /**
     * Function to edit profile of respected user
     *
     * @int user_id
     * @string other fields
     * @param $imageService
     * @return static
     */
    public function updateByUserId($imageService)
    {
        $user_id              = (request('user_id')) ? (int)request('user_id') : \Auth::user()->id;
        $profile              = $this->profile::byUser($user_id)->firstOrNew(['user_id' => $user_id]);
        $profile->first_name  = request('first_name');
        $profile->middle_name = request('middle_name');
        $profile->last_name   = request('last_name');
        $profile->phone       = request('phone');
        $imageService->attachImage(request(), $profile);
        $profile->image       = $profile->image;
        $profile->gender      = request('gender');
        $profile->dob         = request('dob');
        $profile->country_id  = request('country_id');

        $profile->save();
        return $profile;
    }

    /**
     * Function to return respected user's profile
     *
     * @int user_id
     * @return static
     */
    public function findByUserId()
    {
        $user_id = (request('user_id')) ? (int)request('user_id') : \Auth::user()->id;
        $profile = $this->profile::byUser($user_id)->with('country')->first();
        return ($profile) ? $profile : false;
    }

    /**
     * Description: the following method is used to update the respective user's password
     * @author Hassan Mehmood - I2L
     * @return mixed
     */
    public function updateUserPassword()
    {
        $user = $this->user::find(request('user_id'));
        if (!$user) {
            $this->raiseHttpResponseException("idea::general.invalid_user_password");
        }

        if (!Hash::check(request('current_password'), $user->password)) {
            $this->raiseHttpResponseException("idea::general.invalid_user_password");
        }

        $current = Carbon::now();

        //update user request
        $user->password_changed_at = $current;
        $user->password = Hash::make(request('new_password'));
        $user->save();

        return true;
    }
}
