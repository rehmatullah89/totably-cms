<?php

use Illuminate\Database\Seeder;

class AppVersionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('app_versions')->truncate();

        \DB::table('app_versions')->insert(
            [
                [
                    'version' => '1.0',
                    'device_type' => 'android',
                    'update_type' => 'minor',
                    'active' => 1
                ],
                [
                    'version' => '1.0',
                    'device_type' => 'ios',
                    'update_type' => 'minor',
                    'active' => 1
                ]
            ]
        );
    }
}
