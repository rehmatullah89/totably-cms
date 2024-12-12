<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class PushNotificationTopic extends BaseModel
{
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
