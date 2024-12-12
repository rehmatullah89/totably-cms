<?php

use App\Models\Idea\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * insert user role
     */
    public function run()
    {
        \DB::table('roles')->truncate();
        $roles = array(
            array('id' => 1, 'slug' => 'admin'),
            array('id' => 2, 'slug' => 'external'),
            array('id' => 3, 'slug' => 'restaurant_manager'),
        );
        Role::insert($roles);
    }
}
