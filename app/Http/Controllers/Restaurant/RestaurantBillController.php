<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\Restaurant;

use App\Idea\Base\BaseController;
use App\Repositories\Restaurant\RestaurantBillRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantBillController extends BaseController
{
    protected $permissions   = [
        "index"              => ["code" => "restaurant", "action" => "read"],
        "one"                => ["code" => "restaurant", "action" => "read"],
        "billByRestaurantId" => ["code" => "restaurant", "action" => "read"],
        "billByTableId"      => ["code" => "restaurant", "action" => "read"],
    ];

    protected $restaurantBillRepository;

    /**
     * @param RestaurantBillRepository $restaurantBillRepository
     * @param Request $request
     */
    public function __construct(RestaurantBillRepository $restaurantBillRepository, Request $request)
    {
        parent::__construct($request);

        $this->restaurantBillRepository = $restaurantBillRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [];
    }

    /**
     * Description: The following method will fetch all restaurant bills.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->restaurantBillRepository->findAll());
    }

    /**
     * Description: The following method will fetch one restaurant bill.
     *
     * @param int    : the restaurant bill id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->restaurantBillRepository->findOne($id));
    }

    /**
     * Function to return respected bill by restaurant
     *
     * @int restaurant_id
     * @return JsonResponse
     */
    public function billByRestaurantId()
    {
        $restaurant = $this->restaurantBillRepository->findByRestaurantId();
        return ($restaurant) ? $this->successData($restaurant) : $this->failed('idea::general.record_does_not_exist');
    }

    /**
     * Function to return respected bill by table
     *
     * @int table_id
     * @return JsonResponse
     */
    public function billByTableId()
    {
        $restaurant = $this->restaurantBillRepository->findByTableId();
        return ($restaurant) ? $this->successData($restaurant) : $this->failed('idea::general.record_does_not_exist');
    }
}
