<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\Restaurant;

use App\Idea\Base\BaseController;
use App\Models\Idea\RestaurantGallery;
use App\Repositories\Restaurant\RestaurantGalleryRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantGalleryController extends BaseController
{
    protected $permissions   = [
        "index"              => ["code" => "restaurant", "action" => "read"],
        "one"                => ["code" => "restaurant", "action" => "read"],
        "restaurantByUserId" => ["code" => "restaurant", "action" => "read"],
        "destroy"            => ["code" => "restaurant", "action" => "write"],
        "store"              => ["code" => "restaurant", "action" => "write"],
        "update"             => ["code" => "restaurant", "action" => "write"]
    ];

    protected $restaurantGalleryRepository;
    protected $restaurantRepository;
    protected $imageService;

    /**
     * @param RestaurantRepository $restaurantRepository
     * @param RestaurantGalleryRepository $restaurantGalleryRepository
     * @param Request $request
     */
    public function __construct(RestaurantGalleryRepository $restaurantGalleryRepository, RestaurantRepository $restaurantRepository, Request $request)
    {
        parent::__construct($request);

        $this->restaurantGalleryRepository = $restaurantGalleryRepository;
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
        $this->setModel(new RestaurantGallery());
        $this->with = ['restaurant'];
        $this->withImage = true;
        $this->imageName = "image";
        $this->filePath  = "";
        $this->imageService = new ImageService($this->withImage, $this->withImageThumb, $this->imageName, $this->thumbnailName, $this->filePath);
    }

    /**
     * Description: The following method will fetch all Restaurant Gallery Images.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->restaurantGalleryRepository->findAll());
    }

    /**
     * Description: The following method will fetch one Restaurant Gallery Image.
     *
     * @param int    : the restaurant gallery image id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->restaurantGalleryRepository->findOne($id));
    }

    /**
     * Description: The following method will add new image to the restaurant gallery
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function store()
    {
        return $this->success($this->messages['save_success'], $this->restaurantGalleryRepository->saveRestaurantGallery($this->imageService));
    }

    /**
     * Description: The following method will update image to the restaurant gallery
     *
     * @param $id
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success($this->messages['update_success'], $this->restaurantGalleryRepository->updateRestaurantGallery($id, $this->imageService));
    }

    /**
     * Description: The following method will delete Restaurant Gallery Images.
     *
     * @param int    : the restaurant gallery image id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->restaurantGalleryRepository->deleteRestaurantGallery($id, $this->imageService) ? $this->success() : $this->failed();
    }

    /**
     * Function to return respected gallery by restaurant
     *
     * @int restaurant_id
     * @return JsonResponse
     */
    public function galleryByRestaurantId()
    {
        $restaurant = $this->restaurantGalleryRepository->findByRestaurantId();
        return ($restaurant) ? $this->successData($restaurant) : $this->failed('idea::general.record_does_not_exist');
    }
}
