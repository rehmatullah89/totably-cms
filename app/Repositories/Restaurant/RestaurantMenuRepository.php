<?php


namespace App\Repositories\Restaurant;

use App\Idea\Base\BasePaging;
use App\Models\Idea\Restaurant;
use App\Models\Idea\RestaurantMenu;
use App\Idea\Types\ExceptionType;
use App\Repositories\User\UserAccountRepository;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to restaurant menu
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class RestaurantMenuRepository
{
    use ExceptionType;

    protected $logged_in_user;
    protected $logged_in_user_role;
    protected $request;
    protected $restaurant;
    protected $restaurant_menu;
    protected $restaurantRepository;
    protected $userAccountRepository;

    public function __construct(UserAccountRepository $userAccountRepository, RestaurantRepository $restaurantRepository, Restaurant $restaurant, RestaurantMenu $restaurant_menu, Request $request)
    {
        $this->request = $request;
        $this->restaurant = $restaurant;
        $this->restaurant_menu = $restaurant_menu;
        $this->restaurantRepository = $restaurantRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->logged_in_user = \Auth::user()->id;
        $this->logged_in_user_role = $this->userAccountRepository->getRoleByUser($this->logged_in_user);
    }

    /**
     * Description: This function returns all restaurant menu images
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_menu->ByManagerId()->getQuery() : $this->restaurant_menu;
        $query = $query->with('restaurant')->OrderAscending();
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected restaurant menu image
     * @param $id
     * @return Restaurant Menu
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_menu->ByManagerId()->where('tp_restaurant_menu.id', $id)->getQuery() : $this->restaurant_menu;
        return $query->with('restaurant')->find($id);
    }

    /**
     * Function to return respected restaurant's menu
     *
     * @int restaurant_id
     * @param $id
     * @return static
     */
    public function findByRestaurantId($id = 0)
    {
        $restaurant_id      = (request('restaurant_id')) ? (int)request('restaurant_id') : (($id) ? $id : 0);
        $restaurant         = $this->restaurantRepository->findOne($restaurant_id);
        if(!$restaurant) {
            $this->raiseHttpResponseException('access_denied');
        }

        $restaurant_menu   = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_menu->ByManagerId()->getQuery() : $this->restaurant_menu;
        return ($restaurant_menu) ? $restaurant_menu->with('restaurant')->where('restaurant_id', $restaurant_id)->OrderAscending()->get() : false;
    }

    /**
     * Function to save a restaurant Menu
     *
     * @string params
     * @param $imageService
     * @return RestaurantMenu
     */
    public function saveRestaurantMenu($imageService)
    {
        $data = $this->request->all();
        $restaurant_id              = (isset($data['restaurant_id'])) ? (int)$data['restaurant_id'] : 0;

        $restaurant         = $this->restaurantRepository->findOne($restaurant_id);
        if(!$restaurant) {
            $this->raiseHttpResponseException('access_denied');
        }

        $imageService->filePath = "uploads/restaurant_".$restaurant_id."/menu_picture/";
        $imageService->attachImage(request(), $this->restaurant_menu);

        $this->restaurant_menu->name = $data['name'];
        $this->restaurant_menu->image = $this->restaurant_menu->image;
        $this->restaurant_menu->restaurant_id  = $restaurant_id;

        if(isset($data['order'])) {
            $this->restaurant_menu->order = $data['order'];
        }

        $this->restaurant_menu->save();
        return $this->restaurant_menu;
    }

    /**
     * Function to update a restaurant
     *
     * @string params
     * @param $id
     * @param $imageService
     * @return RestaurantMenu
     */
    public function updateRestaurantMenu($id, $imageService)
    {
        if (!$id) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $this->restaurant_menu     = $this->findOne($id);
        if(!$this->restaurant_menu) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $data = $this->request->all();
        $imageService->filePath = "uploads/restaurant_".$this->restaurant_menu->restaurant_id."/menu_picture/";

        if(isset($data['name'])) {
            $this->restaurant_menu->name = $data['name'];
        }

        if(isset($data['order'])) {
            $this->restaurant_menu->order = $data['order'];
        }

        $imageService->attachImage(request(), $this->restaurant_menu);
        $this->restaurant_menu->image = $this->restaurant_menu->image;

        $this->restaurant_menu->save();
        return $this->restaurant_menu;
    }

    /**
     * Description: This function will delete respected restaurant
     * @param $id
     * @param $imageService
     * @return boolean
     * @throws \Exception
     */
    public function deleteRestaurantMenu($id, $imageService)
    {
        $restaurant_menu = $this->findOne($id);
        if (!$restaurant_menu) {
            $this->raiseHttpResponseException('cannot_delete_restaurant_table');
        }

        // delete image first
        $imageService->deleteImage($restaurant_menu);

        return ($restaurant_menu->delete()) ? true : false;
    }
}
