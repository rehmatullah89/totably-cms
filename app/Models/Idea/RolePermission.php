<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 6/8/2017
 * Time: 1:11 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;

class RolePermission extends BaseModel
{
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function scopeByRoleId($query, $id)
    {
        return $query->where('role_id', $id);
    }

    public function scopeSetRolePermission($query, $roleId, $permissionId, $actionId)
    {
        $this->role_id       = $roleId;
        $this->permission_id = $permissionId;
        $this->action_id     = $actionId;
        $this->save();
    }
}
