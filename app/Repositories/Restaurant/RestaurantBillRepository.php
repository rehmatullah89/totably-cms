<?php


namespace App\Repositories\Restaurant;

use App\Idea\Base\BasePaging;
use App\Models\Idea\Restaurant;
use App\Models\Idea\RestaurantBill;
use App\Repositories\User\UserAccountRepository;
use Illuminate\Http\Request;
use App\Idea\Types\ExceptionType;

/**
 * Description: The following repository is used to handle all function related to restaurant bill
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class RestaurantBillRepository
{
    use ExceptionType;

    protected $logged_in_user;
    protected $logged_in_user_role;
    protected $request;
    protected $restaurantRepository;
    protected $restaurantTableRepository;
    protected $userAccountRepository;
    protected $restaurant_bill;

    /**
     * RestaurantBillRepository constructor.
     * @param UserAccountRepository $userAccountRepository
     * @param RestaurantRepository $restaurantRepository
     * @param RestaurantTableRepository $restaurantTableRepository
     * @param RestaurantBill $restaurant_bill
     * @param Request $request
     */
    public function __construct(UserAccountRepository $userAccountRepository, RestaurantRepository $restaurantRepository, RestaurantTableRepository $restaurantTableRepository, RestaurantBill $restaurant_bill, Request $request)
    {
        $this->request = $request;
        $this->restaurantRepository = $restaurantRepository;
        $this->restaurantTableRepository = $restaurantTableRepository;
        $this->userAccountRepository = $userAccountRepository;
        $this->restaurant_bill = $restaurant_bill;
        $this->logged_in_user = \Auth::user()->id;
        $this->logged_in_user_role = $this->userAccountRepository->getRoleByUser($this->logged_in_user);
    }

    /**
     * Description: This function returns all restaurant bill
     * @author Hassan Mehmood - I2L
     */
    public function findAll()
    {
        $query = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_bill->getPaymentsByManagerId() : $this->restaurant_bill;
        $query = new BasePaging($query->FindPayments());
        return $query;
    }

    /**
     * Description: This function returns respected restaurant bill
     * @param $id
     * @return Restaurant Bill
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        $query  = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_bill->getPaymentsByManagerId() : $this->restaurant_bill;
        $result =  $query->FindPayments()->find($id);
        $result->ticket_items = $this->getItemsByTicketId($result->tables->restaurant->location_id, $result->ticket_id);
        return $result;
    }

    /**
     * Function to return respected restaurant's bill
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

        $restaurant_bill   = ($this->logged_in_user_role == "restaurant_manager") ? $this->restaurant_bill->getPaymentsByManagerId($restaurant_id) : $this->restaurant_bill->whereHas('tables', function ($query) use ($restaurant_id) { $query->where('restaurant_id', '=', $restaurant_id); });
        return ($restaurant_bill) ? $restaurant_bill->FindPayments()->get() : false;
    }

    /**
     * Function to return respected table's bill
     *
     * @int table_id
     * @param int $id
     * @return static
     */
    public function findByTableId($id = 0)
    {
        $table_id           = (request('table_id')) ? (int)request('table_id') : (($id) ? $id : 0);
        $table              = $this->restaurantTableRepository->findOne($table_id);
        if(!$table) {
            $this->raiseHttpResponseException('access_denied');
        }

        $restaurant_bill    = $this->restaurant_bill->whereHas('tables', function ($query) use ($table_id) {
            $query->where('id', '=', $table_id);
        });

        return ($restaurant_bill) ? $restaurant_bill->FindPayments()->get() : false;
    }

    /**
     * Function to return respected bill's items
     *
     * @param int $location_id
     * @param int $ticket_id
     * @return array
     */
    public function getItemsByTicketId($location_id = 0, $ticket_id = 0)
    {
        $endpoint   = 'https://api.omnivore.io/1.0/locations/'.$location_id.'/tickets/'.$ticket_id.'/items';
        $items      = doCurlRequest('GET', $endpoint, ['Api-key: 631e42d0131346f8a2217eba1608718c']);

        return (isset($items->_embedded->items)) ? $items->_embedded->items: [];
    }
}
