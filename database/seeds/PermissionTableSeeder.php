<?php

use App\Models\Idea\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->truncate();
        $permissions = array(
            array('id' => 1, 'module' => 'user', 'name' => 'Add User', 'code' => 'add_user'),
            array('id' => 2, 'module' => 'configuration', 'name' => 'Configuration', 'code' => 'configuration'),
            array('id' => 3, 'module' => 'feedback', 'name' => 'Feedback', 'code' => 'feedback'),
            array('id' => 4, 'module' => 'pages', 'name' => 'Pages', 'code' => 'pages'),
            array('id' => 5, 'module' => 'user_roles', 'name' => 'Add User Role', 'code' => 'user_roles'),
            array('id' => 6, 'module' => 'push_notifications', 'name' => 'Manage Push', 'code' => 'push_notifications'),
            array('id' => 7, 'module' => 'restaurant', 'name' => 'Manage Restaurant', 'code' => 'restaurant'),
        );
        Permission::insert($permissions);
    }
}
