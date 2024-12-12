<?php

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Language extends BaseModel
{
    public function scopeByLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }
}
