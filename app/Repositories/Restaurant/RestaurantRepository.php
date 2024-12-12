<?php


namespace App\Repositories\Restaurant;

use App\Idea\Base\BasePaging;
use App\Jobs\SendEmail;
use App\Models\Idea\User;
use App\Models\Idea\Restaurant;
use App\Idea\Types\ExceptionType;
use App\Repositories\User\UserAccountRepository;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to user restaurant etc
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class RestaurantRepository
{
    use ExceptionType;

    protected $logged_in_user;
    protected $logged_in_user_role;
    protected $request;
    protected $user;
    protected $restaurant;
    protected $userAccountRepository;

    public function __construct(UserAccountRepository $userAccountRepository, User $user, Restaurant $restaurant, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
        $this->restaurant = $restaurant;
        $this->userAccountRepository = $userAccountRepository;
        $this->logged_in_user = \Auth::user()->id;
        $this->logged_in_user_role = $this->userAccountRepository->getRoleByUser($this->logged_in_user);
    }

    /**
     * Description: This function returns all restaurants
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        $query = $this->restaurant::FindRestaurants();
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $query->ByManagerId($this->logged_in_user) : $query;
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected restaurant
     * @param $id
     * @return Restaurant
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        $query = $this->restaurant::FindRestaurants();
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $query->ByManagerId($this->logged_in_user) : $query;
        return $query->find($id);
    }

    /**
     * Function to return respected user's restaurant
     *
     * @int user_id
     * @return static
     */
    public function findByUserId()
    {
        $user_id = (request('user_id')) ? (int)request('user_id') : $this->logged_in_user;
        $restaurant = $this->restaurant::FindRestaurants();
        $restaurant = ($this->logged_in_user_role == "restaurant_manager") ? $restaurant->ByManagerId($user_id) : $restaurant->ByUserId($user_id);
        $restaurant = $restaurant->get();
        return ($restaurant) ? $restaurant : false;
    }

    /**
     * Function to save a restaurant
     *
     * @string params
     * @param $imageService
     * @return Restaurant
     */
    public function saveRestaurant($imageService)
    {
        if ($this->logged_in_user_role == "restaurant_manager") {
            $this->raiseHttpResponseException("access_denied");
        }

        $data = $this->request->all();
        $user_id              = (isset($data['user_id'])) ? (int)$data['user_id'] : $this->logged_in_user;

        $this->restaurant->name  = $data['name'];
        $this->restaurant->location_id  = $data['location_id'];
        $this->restaurant->description = (isset($data['description'])) ? $data['description'] : '';
        $this->restaurant->address = $data['address'];
        $this->restaurant->phone   = $data['phone'];
        $this->restaurant->email   = $data['email'];
        $this->restaurant->working_hours = (isset($data['working_hours'])) ? $data['working_hours'] : '';
        $imageService->attachImage(request(), $this->restaurant);
        $this->restaurant->image = $this->restaurant->image;
        $this->restaurant->user_id  = $user_id;

        // create restaurant
        if (!$this->restaurant->save()) {
            $this->raiseHttpResponseException('idea::general.couldnt_add_restaurant_please_try_again_later');
        }

        // once restaurant is created, lets create user with restaurant permission
        $password   =  generate_random_string(8).'&@'.$this->restaurant->id;
        $user       = $this->createRestaurantManager($this->restaurant, $password);

        // assign restaurant manager to a restaurant
        $this->restaurant->manager_id = $user->id;
        $this->restaurant->save();

        $this->notifyRestaurantManager($user, $password);
        return $this->restaurant;
    }

    /**
     * Function to update a restaurant
     *
     * @string params
     * @param $id
     * @param $imageService
     * @return Restaurant
     */
    public function updateRestaurant($id, $imageService)
    {
        if (!$id) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $data                 = $this->request->all();
        $user_id              = (isset($data['user_id'])) ? (int)$data['user_id'] : $this->logged_in_user;

        $restaurant         = $this->restaurant::FindRestaurants();
        $restaurant         = ($this->logged_in_user_role == "restaurant_manager") ? $restaurant->ByManagerId($user_id) : $restaurant->ByUserId($user_id);
        $this->restaurant   = $restaurant->find($id);

        if (!$this->restaurant) {
            $this->raiseHttpResponseException("access_denied");
        }

        if(isset($data['name'])) {
            $this->restaurant->name = $data['name'];
        }

        if(isset($data['description'])) {
            $this->restaurant->description = $data['description'];
        }

        if(isset($data['address'])) {
            $this->restaurant->address = $data['address'];
        }

        if(isset($data['phone'])) {
            $this->restaurant->phone = $data['phone'];
        }

        if(isset($data['email'])) {
            $this->restaurant->email = $data['email'];
        }

        if(isset($data['working_hours'])) {
            $this->restaurant->working_hours = $data['working_hours'];
        }

        $imageService->attachImage(request(), $this->restaurant);
        $this->restaurant->image = $this->restaurant->image;
        $this->restaurant->user_id  = $user_id;

        if (!$this->restaurant->save()) {
            $this->raiseHttpResponseException('idea::general.couldnt_update_restaurant_please_try_again_later');
        }
        return $this->restaurant;
    }

    /**
     * Description: This function will delete respected restaurant
     * @param $id
     * @param $imageService
     * @return boolean
     * @author Hassan Mehmood - I2L
     */
    public function deleteRestaurant($id, $imageService)
    {
        $restaurant     = $this->restaurant::FindRestaurants();
        $restaurant     = ($this->logged_in_user_role == "restaurant_manager") ? $restaurant->ByManagerId($this->logged_in_user) : $restaurant->ByUserId($this->logged_in_user);

        $restaurant     = $restaurant->find($id);

        if (!$restaurant) {
            $this->raiseHttpResponseException('cannot_delete_restaurant');
        }

        // delete image first
        $imageService->deleteImage($restaurant);

        return ($restaurant->delete()) ? true : false;
    }

    /**
     * Description: This function will create restaurant manager
     * @param $restaurant
     * @param $password
     * @return User
     * @author Hassan Mehmood - I2L
     */
    public function createRestaurantManager($restaurant, $password)
    {
        $userdata = [
            'username' => $restaurant->email,
            'email' => $restaurant->email,
            'password' => $password,
            'name' => 'Restaurant Manager',
            'active' => 1,
            'role_id' => 3,
        ];
        return $this->userAccountRepository->registerNewAdmin($userdata);
    }

    /**
     * Description: This function will create restaurant manager
     * @param $user
     * @param $password
     * @return boolean
     * @author Hassan Mehmood - I2L
     */
    public function notifyRestaurantManager($user, $password)
    {
        //fill the data array
        $data = array(
            'template' => 'emails.restaurant-manager',
            'subject' => env('MAIL_FROM_NAME') . ', Restaurant Manager',
            'to' => ['name' => $user->name, 'email' => $user->email],
            'username' => $user->username,
            'code' => $password,
            'app_url' => mail_url()
        );
        dispatch(new SendEmail($data));

        return true;
    }
}
