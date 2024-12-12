<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:11 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class RestaurantBill extends BaseModel
{
    protected $table = 'tp_payment_logs';
    protected $attributes = ['ticket_items'];

    /**
     * Set bill items.
     *
     * @param  string  $value
     * @return void
     */
    public function setTicketItemsAttribute($value)
    {
        $this->attributes['ticket_items'] = $value;
    }

    public function tables()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id', 'id');
    }

    public function scopeFindPayments($query)
    {
        return $query->with(['tables', 'tables.restaurant', 'tables.restaurant.manager']);
    }

    public function scopeByTableId($query, $id)
    {
        return $query->where('table_id', $id);
    }

    public function getPaymentsByManagerId($restaurantId = null, $table = null)
    {
        $payments = $this->select('tp_payment_logs.*')
                    ->join('tp_restaurant_tables', 'tp_restaurant_tables.id', '=', 'tp_payment_logs.table_id')
                    ->join('tp_restaurants', 'tp_restaurant_tables.restaurant_id', '=' , 'tp_restaurants.id' )
                    ->join('users', 'users.id', '=', 'tp_restaurants.manager_id');

        if ( $restaurantId )
            $payments->where('tp_restaurants.id', $restaurantId);

        if ( $table )
            $payments->where('tp_restaurant_tables.id'. $table);

        return $payments->where('users.id', \Auth::user()->id);
    }
}
