<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('RoleTableSeeder');
        $this->call('UserTableSeeder');
        $this->call('AppVersionTableSeeder');
        $this->call('ConfigurationTableSeeder');
        $this->call('CountryTableSeeder');
        $this->call('IPFilterTableSeeder');
        $this->call('LanguagesTableSeeder');
        $this->call('PageTableSeeder');
        $this->call('PermissionTableSeeder');
        $this->call('ActionTableSeeder');
        $this->call('ProfileTableSeeder');
        $this->call('RolePermissionTableSeeder');
        $this->call('FeedbackTableSeeder');
        $this->call('PushNotificationHistoryTableSeeder');
        $this->call('RestaurantTableSeeder');
        $this->call('RestaurantTableTableSeeder');
        $this->call('RestaurantBillTableSeeder');
    }
}
