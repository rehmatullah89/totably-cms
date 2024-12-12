<?php

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;
use App\Idea\Base\BaseTranslationModel;
use App\Idea\Types\TranslatableType;

class Role extends BaseModel
{
    use TranslatableType;
    public $translatedAttributes = ['title'];

    public function rolePermission()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function userRole()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    public function scopeRoleById($query, $id)
    {
        return $query->where('id', $id);
    }
}
