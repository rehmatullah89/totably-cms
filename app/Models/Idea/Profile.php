<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/7/2017
 * Time: 12:49 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Profile extends BaseModel
{
    protected $fillable = ['user_id'];

    /*
     * this function is to set profile relation with user
     * a profile can be owned by one user only
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function scopeByUser($query, $id)
    {
        return $query->where('user_id', $id);
    }
}
