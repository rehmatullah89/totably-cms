<?php

namespace App\Models\Idea;

use Carbon\Carbon;
use App\Idea\Base\BaseModel;
use App\Jobs\SendEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;
use Intervention\Image\Facades\Image;

class User extends BaseModel implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public static $jwtCmsKey = "nhq";
    protected $hidden
        = [
            'password',
            'jwt_sign',
            'email_confirm_code',
            'email_confirm_expiry',
            'email_confirmed_at',
            'password_change_code',
            'password_change_expiry',
            'password_changed_at',
        ];
    protected $fillable = ['email', 'name', 'image', 'username'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    /*
     * this function is set user relation with Token
     * a user can have many Token
     */
    public function userProvidersTokens()
    {
        return $this->hasMany(UserProviderToken::class, 'user_id');
    }

    /*
     * this function is set user relation with Profile
     * a user can have many one profile
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /*
     * this function is set user relation with Devices
     * a user can have many devices
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function configurations()
    {
        return $this->hasMany(Configuration::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /*
     * this function is set user relation with roles
     * a user can have many roles
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /*
     * this function is set user relation with Notifications
     * a user can have many Notifications
     */
    public function notifications()
    {
        return $this->hasMany(UserNotifications::class, 'user_id');
    }

    /*
     * this function is set user relation with Devices
     * a user have on only one latest device
     */
    public function latestDevice()
    {
        return $this->hasOne(Device::class)->latest();
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {

        //create jwt sign on the fly
        if (!$this->jwt_sign) {
            $this->updateJWTSign();
        }

        $claims = ["jwt_sign" => $this->jwt_sign];
        if ($this->isCmsUser()) {
            $claims[self::$jwtCmsKey] = 1;
        }

        return $claims;
    }

    /*
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('name', 'LIKE', '%'.$key.'%')->orWhere('username', 'LIKE', '%'.$key.'%');
    }

    /*
     * Functions
     */
    public function updateJWTSign()
    {
        $this->jwt_sign = Str::random();
        $this->save();
    }

    /*
     * this function is to activate user account
     * */
    public function activate()
    {
        $this->active = 1;

        return $this->save();
    }

    public function linkDevice()
    {
        $device = app("DeviceInfo");
        $device->user_id = $this->id;

        return $device->save();
    }

    public function linkToken($deviceToken)
    {

        $device = app("DeviceInfo");
        $device->token = $deviceToken;

        return $device->save();
    }

    public function isCmsUser()
    {
        $roles       = $this->roles()->get();
        $hasExternal = false;
        foreach ($roles as $role) {
            if ($role->slug == 'external') {
                $hasExternal = true;
            }
        }

        if ($hasExternal && count($roles) == 1) {
            return false;
        }

        return true;
    }

    /*
     * this function is to assign External role to a user
     * */
    public function assignExternalRole()
    {
        $role = Role::where("slug", "external")->first();
        $this->roles()->attach($role->id);
    }

    /*
     * this function is to assign Admin role to a user
     */
    public function assignAdminRole()
    {
        $role = Role::where("slug", "admin")->first();
        $this->roles()->attach($role->id);
    }

    /*
     * this function is to assign a role and a permission to a user
     * */
    public function assignRolePermission($roleId)
    {
        //delete existing user
        UserRole::byUserId($this->id)->delete();

        foreach ($roleId as $id) {
            $role = Role::where("id", $id)->first();
            if ($role) {
                $this->roles()->attach($role->id);
            } else {
                break;
            }
        }
    }


    //TODO DEFINE WHAT TO RETURN
    public function getMoreInfo()
    {
        return [];
    }

    /*
     * this function setup the new user ,set user role ,fire an event
     * */
    public function setUpNewUser()
    {
        //fire new user event
        $this->notifyUser();
        $this->notifyOwner();

        //assign external role
        $this->assignExternalRole();
    }

    /*
     * this function is to link device Id with the user id
     * using a Token
     * */
    public function linkDeviceIdAndPushToken()
    {
        //link the current device
        $this->linkDevice();

        //update device token
        if (request('user_device_token')) {
            $this->linkToken(request('user_device_token'));
        }
    }

    /*
     * this function is to add a profile image to a user check if
     * image is valid ,we don't need to raise any exception here,
     * so if the image is not valid or we couldn't resize it, then
     * return false
     *
     * @param $user
     */
    public function saveUserProfileImage($file)
    {

        try {
            //create new name
            $folderPath = 'uploads/' . $this->id . '/profile_picture/';
            $folder = public_path($folderPath);
            $name = str_replace(' ', '_', rand(5000, 100000) . "_" . $file->getClientOriginalName());

            //create folder if not exist
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
                chmod($folder, 0777);
            }
            //save the image after resize it
            Image::make($file->getRealPath())->fit(250, 250)->save($folder . $name);
            $profile = Profile::byUser($this->id)->first();
            $profile->image = $folderPath . $name;
            $profile->save();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * this function is to return user's info
     *
     * @param      $user
     * @param bool $withToken
     *
     * @return array
     * @internal param $token
     */
    public function returnUser()
    {
        $userProfile = $profile = Profile::byUser($this->id)->first();
        //return user info
        $toReturn = [
            'user' => [
                "id" => $this->id,
                "username" => $this->username,
                "user_full_name" => $this->name,
                "first_name" => !empty($userProfile->first_name) ? $userProfile->first_name : '',
                "last_name" => !empty($userProfile->last_name) ? $userProfile->last_name : '',
                "phone" => !empty($userProfile->phone) ? $userProfile->phone : '',
                "email" => $this->email,
                "user_profile_picture" => !empty($userProfile->image) ? $userProfile->image : '',
                "active" => $this->active
            ],
            'info' => $this->getMoreInfo(),
        ];

        return $toReturn;
    }

    /*
     * generate Token
     * */
    public function generateJWTToken($ttl = 0)
    {
        if ($ttl) {
            JWTAuth::factory()->setTTL($ttl);
        }

        return JWTAuth::fromUser($this);
    }

    /*
     * this function is to create an instance of a new user from
     * \App\Models\Idea\User if exists else create user instance from
     * \Idea\Models\User
     * */
    protected function getUserClassInstance()
    {
        if (class_exists('\App\Models\Idea\User')) {
            return new \App\Models\Idea\User();
        }

        return new User();
    }


    public function notifyUser()
    {
        if (config('auth.verify_emails') && $this->email) {
            $this->sendActivateEmail();
        } else {
            $this->activate();
            $this->sendWelcomeEmail();
        }
    }

    private function sendActivateEmail()
    {
        $this->email_confirm_code = str_random(5);
        $this->email_confirm_expiry = Carbon::now()->addMinutes(60);
        $this->save();

        $data = array(
            'template' => 'emails.verify',
            'subject' => env('MAIL_FROM_NAME').', Verify your email',
            'to' => ['name' => $this->name, 'email' => $this->email],
            'username' => $this->username,
            'code' => $this->email_confirm_code,
        );

        dispatch(new SendEmail($data));

        return true;
    }

    private function sendWelcomeEmail()
    {
        if (!$this->email) {
            return true;
        }
        $data = array(
            'template' => 'emails.welcome',
            'subject' => 'Welcome to '.env('MAIL_FROM_NAME').'!',
            'to' => ['name' => $this->name, 'email' => $this->email],
            'username' => $this->username,
        );

        // dispatch(new SendEmail($data));

        return true;
    }

    public function notifyOwner()
    {
        //get the facebook user id
        $facebookToken = UserProviderToken::byUser($this->id)->facebook()->first();

        //fill the data array
        $data = array(
            'template' => 'emails.notify-owner',
            'sendToOwner' => true,
            'subject' => env('MAIL_FROM_NAME').', New User!',
            'user_id' => $this->id,
            'user_full_name' => $this->name,
            'user_email' => $this->email,
            'user_facebook_user_id' => isset($facebookToken->id) ? $facebookToken->token_id : "",
            'total_users_count' => User::count(),
        );
        dispatch(new SendEmail($data));
    }

    public function managerRestaurantTables()
    {
        return $this->hasManyThrough(RestaurantTable::class, Restaurant::class, 'manager_id', 'restaurant_id');
    }

    public function managerRestaurantGallery()
    {
        return $this->hasManyThrough(RestaurantGallery::class, Restaurant::class, 'manager_id', 'restaurant_id');
    }

    public function managerRestaurantMenu()
    {
        return $this->hasManyThrough(RestaurantMenu::class, Restaurant::class, 'manager_id', 'restaurant_id');
    }

    /*
     * this function is set user relation with restaurants
     * a user can have many restaurants
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
