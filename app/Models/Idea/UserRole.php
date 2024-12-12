<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/28/2017
 * Time: 6:40 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class UserRole extends BaseModel
{
    public function roles()
    {
        return $this->hasOne(Role::class, 'id');
    }

    public function findRoleById($id)
    {
        return Role::where("id", $id)->first();
    }

    public function scopeByUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }
}
