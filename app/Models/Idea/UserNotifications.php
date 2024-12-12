<?php

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class UserNotifications extends BaseModel
{
    public $hideTimestamp = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByTarget($query, $userId)
    {
        return $query->where('target_id', $userId);
    }
}
