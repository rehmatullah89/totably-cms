<?php
/**
 * Created by PhpStorm.
 * User: Abed Bilani
 * Date: 6/7/2017
 * Time: 12:56 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;
use App\Idea\Types\TranslatableType;

class Country extends BaseModel
{
    use TranslatableType;
    public $translatedAttributes = ['name'];

    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }
}
