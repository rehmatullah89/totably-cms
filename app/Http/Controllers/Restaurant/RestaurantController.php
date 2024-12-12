<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\Restaurant;

use App\Idea\Base\BaseController;
use App\Models\Idea\Restaurant;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantController extends BaseController
{
    protected $permissions   = [
        "index"              => ["code" => "restaurant", "action" => "read"],
        "one"                => ["code" => "restaurant", "action" => "read"],
        "restaurantByUserId" => ["code" => "restaurant", "action" => "read"],
        "destroy"            => ["code" => "restaurant", "action" => "write"],
        "store"              => ["code" => "restaurant", "action" => "write"],
        "update"             => ["code" => "restaurant", "action" => "write"]
    ];

    protected $restaurantRepository;
    protected $imageService;

    /**
     * @param RestaurantRepository $restaurantRepository
     * @param Request $request
     */
    public function __construct(RestaurantRepository $restaurantRepository, Request $request)
    {
        parent::__construct($request);
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'store' => [
                'location_id' => 'required|unique:tp_restaurants',
                'name' => 'required|unique:tp_restaurants',
                'email' => 'required',
                'user_id' => 'required',
            ]
        ];
    }

    /**
     * Init
     */
    protected function init()
    {
        $this->setModel(new Restaurant());
        $this->with = ['user'];
        $this->withImage = true;
        $this->imageName = "image";
        $this->filePath = "uploads/{user_id}/restaurant_picture/";
        $this->imageService = new ImageService($this->withImage, $this->withImageThumb, $this->imageName, $this->thumbnailName, $this->filePath);
    }

    /**
     * Description: The following method will fetch all Restaurants.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->restaurantRepository->findAll());
    }

    /**
     * Description: The following method will fetch one Restaurant.
     *
     * @param int    : the restaurant id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->restaurantRepository->findOne($id));
    }

    /**
     * Description: The following method will add new restaurant to the system
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function store()
    {
        return $this->success($this->messages['save_success'], $this->restaurantRepository->saveRestaurant($this->imageService));
    }

    /**
     * Description: The following method will update restaurant
     *
     * @param $id
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success($this->messages['update_success'], $this->restaurantRepository->updateRestaurant($id, $this->imageService));
    }

    /**
     * Description: The following method will delete User's Restaurant.
     *
     * @param int    : the restaurant id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->restaurantRepository->deleteRestaurant($id, $this->imageService) ? $this->success() : $this->failed();
    }

    /**
     * Function to return respected restaurants by user
     *
     * @int user_id
     * @return JsonResponse
     */
    public function restaurantByUserId()
    {
        $restaurant = $this->restaurantRepository->findByUserId();
        return ($restaurant) ? $this->successData($restaurant) : $this->failed('idea::general.record_does_not_exist');
    }
}
