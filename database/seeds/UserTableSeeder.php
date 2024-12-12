<?php

use App\Models\Idea\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * insert admin user
     */
    public function run()
    {
        \DB::table('users')->truncate();

        $defaultUser = new User();
        $defaultUser->email = 'default@ideatolife.me';
        $defaultUser->password = Hash::make('admi2312yech');
        $defaultUser->name = 'default';
        $defaultUser->username = 'default@ideatolife.me';
        $defaultUser->active = 1;
        $defaultUser->getJWTCustomClaims();
        $defaultUser->assignAdminRole();
        $defaultUser->save();

        $admin = new User();
        $admin->email = 'admin@ideatolife.me';
        $admin->password = Hash::make('admin1asd12h');
        $admin->name = 'admin';
        $admin->username = 'admin@ideatolife.me';
        $admin->active = 1;
        $admin->getJWTCustomClaims();
        $admin->assignAdminRole();
        $admin->save();

        $admin = new User();
        $admin->email = 'test.user@ideatolife.me';
        $admin->password = Hash::make('test123');
        $admin->name = 'Test User';
        $admin->username = 'test.user@ideatolife.me';
        $admin->active = 1;
        $admin->getJWTCustomClaims();
        //assign external role
        $admin->assignExternalRole();
        $admin->save();
    }
}
