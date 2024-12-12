<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:11 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class RestaurantTable extends BaseModel
{
    protected $table = 'tp_restaurant_tables';

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function bill()
    {
        return $this->hasMany(RestaurantBill::class);
    }

    public function scopeByRestaurantId($query, $id)
    {
        return $query->where('restaurant_id', $id);
    }

    public function ByManagerId()
    {
        return \Auth::user()->managerRestaurantTables()->select('tp_restaurant_tables.*');
    }
}
