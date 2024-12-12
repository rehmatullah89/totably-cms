<?php

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class UserProviderToken extends BaseModel
{
    protected $fillable = ['from', 'user_id'];

    /*
     * Scopes
     */
    public function scopeFacebook($query)
    {
        return $query->where("from", "facebook");
    }

    /*
     * Scopes
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where("user_id", $userId);
    }
}
