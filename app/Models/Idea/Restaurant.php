<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:11 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Restaurant extends BaseModel
{
    protected $table = 'tp_restaurants';

    /*
     * this function is set restaurant relation with gallery
     * a restaurant can have many galleries
     */
    public function restaurantGallery()
    {
        return $this->hasMany(RestaurantGallery::class);
    }

    /*
     * this function is set restaurant relation with gallery
     * a restaurant can have many galleries
     */
    public function restaurantTable()
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function scopeFindRestaurants($query)
    {
        return $query->with("user")->with("manager");
    }

    public function scopeByUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }

    public function scopeByManagerId($query, $id)
    {
        return $query->where('manager_id', $id);
    }
}
