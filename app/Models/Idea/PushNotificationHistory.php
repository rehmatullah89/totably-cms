<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 7/5/2017
 * Time: 4:53 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class PushNotificationHistory extends BaseModel
{
    public function scopeById($query, $id)
    {
        if ($id) {
            return $query->where('id', $id);
        }

        return $query;
    }

    public function target()
    {
        return $this->belongsTo(User::class);
    }
}
