<?php

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Device extends BaseModel
{
    public function scopeByType($query, $type = null)
    {
        if (is_array($type)) {
            return $query->whereIn('type', $type);
        }
        return $query;
    }
}
