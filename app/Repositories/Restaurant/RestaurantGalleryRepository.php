<?php


namespace App\Repositories\Restaurant;

use App\Idea\Base\BasePaging;
use App\Models\Idea\Restaurant;
use App\Models\Idea\RestaurantGallery;
use App\Idea\Types\ExceptionType;
use App\Repositories\User\UserAccountRepository;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to restaurant gallery
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class RestaurantGalleryRepository
{
    use ExceptionType;

    protected $logged_in_user;
    protected $logged_in_user_role;
    protected $request;
    protected $restaurant;
    protected $restaurant_gallery;
    protected $restaurantRepository;
    protected $userAccountRepository;

    public function __construct(UserAccountRepository $userAccountRepository, RestaurantRepository $restaurantRepository, Restaurant $restaurant, RestaurantGallery $restaurant_gallery, Request $request)
    {
        $this->request = $request;
        $this->restaurant = $restaurant;
        $this->restaurant_gallery = $restaurant_gallery;
        $this->restaurantRepository = $restaurantRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->logged_in_user = \Auth::user()->id;
        $this->logged_in_user_role = $this->userAccountRepository->getRoleByUser($this->logged_in_user);
    }

    /**
     * Description: This function returns all restaurant gallery images
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_gallery->ByManagerId()->getQuery() : $this->restaurant_gallery;
        $query = $query->with('restaurant')->OrderAscending();
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected restaurant gallery image
     * @param $id
     * @return Restaurant Gallery
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_gallery->ByManagerId()->where('tp_restaurant_gallery.id', $id)->getQuery() : $this->restaurant_gallery;
        return $query->with('restaurant')->find($id);
    }

    /**
     * Function to return respected restaurant's gallery
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

        $restaurant_gallery   = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_gallery->ByManagerId()->getQuery() : $this->restaurant_gallery;
        return ($restaurant_gallery) ? $restaurant_gallery->with('restaurant')->where('restaurant_id', $restaurant_id)->OrderAscending()->get() : false;
    }

    /**
     * Function to save a restaurant Gallery
     *
     * @string params
     * @param $imageService
     * @return RestaurantGallery
     */
    public function saveRestaurantGallery($imageService)
    {
        $data = $this->request->all();
        $restaurant_id              = (isset($data['restaurant_id'])) ? (int)$data['restaurant_id'] : 0;

        $restaurant         = $this->restaurantRepository->findOne($restaurant_id);
        if(!$restaurant) {
            $this->raiseHttpResponseException('access_denied');
        }

        $imageService->filePath = "uploads/restaurant_".$restaurant_id."/gallery_picture/";

        $this->restaurant_gallery->name = $data['name'];
        $imageService->attachImage(request(), $this->restaurant_gallery);
        $this->restaurant_gallery->image = $this->restaurant_gallery->image;
        $this->restaurant_gallery->restaurant_id  = $restaurant_id;

        if(isset($data['order'])) {
            $this->restaurant_gallery->order = $data['order'];
        }

        $this->restaurant_gallery->save();
        return $this->restaurant_gallery;
    }

    /**
     * Function to update a restaurant
     *
     * @string params
     * @param $id
     * @param $imageService
     * @return RestaurantGallery
     */
    public function updateRestaurantGallery($id, $imageService)
    {
        if (!$id) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $this->restaurant_gallery     = $this->findOne($id);
        if(!$this->restaurant_gallery) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $data                   = $this->request->all();
        $imageService->filePath = "uploads/restaurant_".$this->restaurant_gallery->restaurant_id."/gallery_picture/";
        $imageService->attachImage(request(), $this->restaurant_gallery);

        if(isset($data['name'])) {
            $this->restaurant_gallery->name = $data['name'];
        }

        if(isset($data['order'])) {
            $this->restaurant_gallery->order = $data['order'];
        }

        $this->restaurant_gallery->image = $this->restaurant_gallery->image;

        $this->restaurant_gallery->save();
        return $this->restaurant_gallery;
    }

    /**
     * Description: This function will delete respected restaurant
     * @param $id
     * @param $imageService
     * @return boolean
     * @throws \Exception
     */
    public function deleteRestaurantGallery($id, $imageService)
    {
        $restaurant_gallery = $this->findOne($id);
        if (!$restaurant_gallery) {
            $this->raiseHttpResponseException('cannot_delete_restaurant_table');
        }

        // delete image first
        $imageService->deleteImage($restaurant_gallery);

        return ($restaurant_gallery->delete()) ? true : false;
    }
}
