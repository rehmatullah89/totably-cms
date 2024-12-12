<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:25 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class Permission extends BaseModel
{
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}
