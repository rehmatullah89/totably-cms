<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/16/2017
 * Time: 2:59 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Feedbacks extends BaseModel
{

    protected $table = 'feedback';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }

    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeByTarget($query, $id)
    {
        return $query->where('id', $id);
    }
}
