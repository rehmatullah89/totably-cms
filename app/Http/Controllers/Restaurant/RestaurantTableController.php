<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 2:39 PM
 */

namespace App\Http\Controllers\Restaurant;

use App\Idea\Base\BaseController;
use App\Repositories\Restaurant\RestaurantTableRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RestaurantTableController extends BaseController
{
    protected $permissions   = [
        "index"              => ["code" => "restaurant", "action" => "read"],
        "one"                => ["code" => "restaurant", "action" => "read"],
        "restaurantByUserId" => ["code" => "restaurant", "action" => "read"],
        "destroy"            => ["code" => "restaurant", "action" => "write"],
        "store"              => ["code" => "restaurant", "action" => "write"],
        "update"             => ["code" => "restaurant", "action" => "write"]
    ];

    protected $restaurantTableRepository;

    /**
     * @param RestaurantTableRepository $restaurantTableRepository
     * @param Request $request
     */
    public function __construct(RestaurantTableRepository $restaurantTableRepository, Request $request)
    {
        parent::__construct($request);

        $this->restaurantTableRepository = $restaurantTableRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            'store' => [
                'code.*'       => 'required',
                'restaurant_id' => 'required|exists:tp_restaurants,id',
            ],
            'update' => [
                'code'         => 'required'
            ]
        ];
    }

    /**
     * Description: The following method will fetch all restaurant tables.
     * @return JsonResponse success
     */
    public function index()
    {
        return $this->successData($this->restaurantTableRepository->findAll());
    }

    /**
     * Description: The following method will fetch one restaurant table.
     *
     * @param int    : the restaurant table id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->restaurantTableRepository->findOne($id));
    }

    /**
     * Description: The following method will add new tables to the restaurant
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function store()
    {
        return $this->success($this->messages['save_success'], $this->restaurantTableRepository->saveRestaurantTable());
    }

    /**
     * Description: The following method will update table to the restaurant
     *
     * @param $id
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success($this->messages['update_success'], $this->restaurantTableRepository->updateRestaurantTable($id));
    }

    /**
     * Description: The following method will delete Restaurant Table.
     *
     * @param int    : the restaurant table id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->restaurantTableRepository->deleteRestaurantTable($id) ? $this->success() : $this->failed();
    }

    /**
     * Function to return respected table by restaurant
     *
     * @int restaurant_id
     * @return JsonResponse
     */
    public function tableByRestaurantId()
    {
        $restaurant = $this->restaurantTableRepository->findByRestaurantId();
        return ($restaurant) ? $this->successData($restaurant) : $this->failed('idea::general.record_does_not_exist');
    }
}
