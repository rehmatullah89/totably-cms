<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\Restaurant;

use App\Idea\Base\BaseController;
use App\Models\Idea\RestaurantMenu;
use App\Repositories\Restaurant\RestaurantMenuRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantMenuController extends BaseController
{
    protected $permissions   = [
        "index"              => ["code" => "restaurant", "action" => "read"],
        "one"                => ["code" => "restaurant", "action" => "read"],
        "restaurantByUserId" => ["code" => "restaurant", "action" => "read"],
        "destroy"            => ["code" => "restaurant", "action" => "write"],
        "store"              => ["code" => "restaurant", "action" => "write"],
        "update"             => ["code" => "restaurant", "action" => "write"]
    ];

    protected $restaurantMenuRepository;
    protected $restaurantRepository;
    protected $imageService;

    /**
     * @param RestaurantRepository $restaurantRepository
     * @param RestaurantMenuRepository $restaurantMenuRepository
     * @param Request $request
     */
    public function __construct(RestaurantMenuRepository $restaurantMenuRepository, RestaurantRepository $restaurantRepository, Request $request)
    {
        parent::__construct($request);

        $this->restaurantMenuRepository = $restaurantMenuRepository;
        $this->restaurantRepository = $restaurantRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'store' => [
                'name' => 'required',
                'restaurant_id' => 'required',
            ]
        ];
    }

    /**
     * Init
     */
    protected function init()
    {
        $this->setModel(new RestaurantMenu());
        $this->with = ['restaurant'];
        $this->withImage = true;
        $this->imageName = "image";
        $this->filePath  = "";
        $this->imageService = new ImageService($this->withImage, $this->withImageThumb, $this->imageName, $this->thumbnailName, $this->filePath);
    }

    /**
     * Description: The following method will fetch all Restaurant Menu Images.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->restaurantMenuRepository->findAll());
    }

    /**
     * Description: The following method will fetch one Restaurant Menu Image.
     *
     * @param int    : the restaurant menu image id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->restaurantMenuRepository->findOne($id));
    }

    /**
     * Description: The following method will add new image to the restaurant menu
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function store()
    {
        return $this->success($this->messages['save_success'], $this->restaurantMenuRepository->saveRestaurantMenu($this->imageService));
    }

    /**
     * Description: The following method will update image to the restaurant menu
     *
     * @param $id
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success($this->messages['update_success'], $this->restaurantMenuRepository->updateRestaurantMenu($id, $this->imageService));
    }

    /**
     * Description: The following method will delete Restaurant Menu Images.
     *
     * @param int    : the restaurant menu image id
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function destroy($id)
    {
        return $this->restaurantMenuRepository->deleteRestaurantMenu($id, $this->imageService) ? $this->success() : $this->failed();
    }

    /**
     * Function to return respected menu by restaurant
     *
     * @int restaurant_id
     * @return JsonResponse
     */
    public function menuByRestaurantId()
    {
        $restaurant = $this->restaurantMenuRepository->findByRestaurantId();
        return ($restaurant) ? $this->successData($restaurant) : $this->failed('idea::general.record_does_not_exist');
    }
}
