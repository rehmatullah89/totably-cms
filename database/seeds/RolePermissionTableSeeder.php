<?php

use App\Models\Idea\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('role_permissions')->truncate();
        $rolePermissions = array(
            array('permission_id' => 1, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 1, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 1, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 1, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 2, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 2, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 2, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 2, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 3, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 3, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 3, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 3, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 4, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 4, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 4, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 4, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 5, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 5, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 5, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 5, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 6, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 6, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 6, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 6, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 7, 'role_id' => 1, 'action_id' => 1),
            array('permission_id' => 7, 'role_id' => 1, 'action_id' => 2),
            array('permission_id' => 7, 'role_id' => 2, 'action_id' => 1),
            array('permission_id' => 7, 'role_id' => 2, 'action_id' => 2),
            array('permission_id' => 7, 'role_id' => 3, 'action_id' => 1),
            array('permission_id' => 7, 'role_id' => 3, 'action_id' => 2)
        );
        RolePermission::insert($rolePermissions);
    }
}
