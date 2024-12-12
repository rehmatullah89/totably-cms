<?php


namespace App\Repositories\Restaurant;

use App\Idea\Base\BasePaging;
use App\Models\Idea\Restaurant;
use App\Models\Idea\RestaurantTable;
use App\Idea\Types\ExceptionType;
use App\Repositories\User\UserAccountRepository;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to restaurant table
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class RestaurantTableRepository
{
    use ExceptionType;

    protected $logged_in_user;
    protected $logged_in_user_role;
    protected $request;
    protected $restaurantRepository;
    protected $userAccountRepository;
    protected $restaurant_table;

    /**
     * RestaurantTableRepository constructor.
     * @param UserAccountRepository $userAccountRepository
     * @param RestaurantRepository $restaurantRepository
     * @param RestaurantTable $restaurant_table
     * @param Request $request
     */
    public function __construct(UserAccountRepository $userAccountRepository, RestaurantRepository $restaurantRepository, RestaurantTable $restaurant_table, Request $request)
    {
        $this->request = $request;
        $this->restaurantRepository = $restaurantRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->restaurant_table = $restaurant_table;
        $this->logged_in_user = \Auth::user()->id;
        $this->logged_in_user_role = $this->userAccountRepository->getRoleByUser($this->logged_in_user);
    }

    /**
     * Description: This function returns all restaurant table images
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_table->ByManagerId()->getQuery() : $this->restaurant_table;
        $query = $query->with('restaurant');
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected restaurant table image
     * @param $id
     * @return Restaurant Table
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_table->ByManagerId()->where('tp_restaurant_tables.id', $id)->getQuery() : $this->restaurant_table;
        return $query->with('restaurant')->find($id);
    }

    /**
     * Function to return respected restaurant's table
     *
     * @int restaurant_id
     * @param int $id
     * @return static
     */
    public function findByRestaurantId($id = 0)
    {
        $restaurant_id      = (request('restaurant_id')) ? (int)request('restaurant_id') : (($id) ? $id : 0);
        $restaurant         = $this->restaurantRepository->findOne($restaurant_id);
        if(!$restaurant) {
            $this->raiseHttpResponseException('access_denied');
        }

        $restaurant_table   = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_table->ByManagerId()->getQuery() : $this->restaurant_table;
        return ($restaurant_table) ? $restaurant_table->with('restaurant')->where('restaurant_id', $restaurant_id)->get() : false;
    }

    /**
     * Function to save a restaurant Table
     *
     * @string params
     * @return bool
     */
    public function saveRestaurantTable()
    {
        $data               = $this->request->all();
        $restaurant_id      = (isset($data['restaurant_id'])) ? (int)$data['restaurant_id'] : 0;

        $restaurant         = $this->restaurantRepository->findOne($restaurant_id);
        if(!$restaurant) {
            $this->raiseHttpResponseException('access_denied');
        }

        if(isset($data['code']) && !empty($data['code'])) {
            foreach ($data['code'] as $table) {
                $this->restaurant_table = new RestaurantTable();
                $this->restaurant_table->code = $table;
                $this->restaurant_table->restaurant_id  = $restaurant_id;

                // check if the same name already exists for the same restaurant
                $restaurant_table = RestaurantTable::where([
                    'code' => $table,
                    'restaurant_id' => $restaurant_id
                ])->first();

                if(!$restaurant_table) {
                    $this->restaurant_table->save();
                }
            }
        } else {
            $this->raiseHttpResponseException('idea::general.invalid_request');
        }

        return true;
    }

    /**
     * Function to update a restaurant
     *
     * @string params
     * @param $id
     * @return RestaurantTable
     */
    public function updateRestaurantTable($id)
    {
        if (!$id) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $data                       = $this->request->all();
        $this->restaurant_table     = $this->findOne($id);
        if(!$this->restaurant_table) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        // check if the same name already exists for the same restaurant
        $restaurant_table = RestaurantTable::where([
            ['id','<>', $id],
            ['code','=', $data['code']],
            ['restaurant_id', '=', $this->restaurant_table->restaurant_id]
        ])->first();

        if($restaurant_table) {
            $this->raiseHttpResponseException('already exists.');
        }

        $this->restaurant_table->code = $data['code'];
        $this->restaurant_table->save();
        return $this->restaurant_table;
    }

    /**
     * Description: This function will delete respected restaurant
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteRestaurantTable($id)
    {
        $restaurant_table = $this->findOne($id);
        if (!$restaurant_table) {
            $this->raiseHttpResponseException('cannot_delete_restaurant_table');
        }
        return ($restaurant_table->delete()) ? true : false;
    }
}
