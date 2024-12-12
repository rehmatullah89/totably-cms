<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:11 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class RestaurantGallery extends BaseModel
{
    protected $table = 'tp_restaurant_gallery';

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function ByManagerId()
    {
        return \Auth::user()->managerRestaurantGallery()->select('tp_restaurant_gallery.*');
    }

    public function scopeByRestaurantId($query, $id)
    {
        return $query->where('restaurant_id', $id);
    }

    public function scopeOrderDescending($query)
    {
        return $query->orderBy('order','DESC');
    }

    public function scopeOrderAscending($query)
    {
        return $query->orderBy('order','ASC');
    }
}
