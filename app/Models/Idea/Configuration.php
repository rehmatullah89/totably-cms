<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/16/2017
 * Time: 10:07 AM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Configuration extends BaseModel
{

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
}
