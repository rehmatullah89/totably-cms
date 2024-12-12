<?php

use Illuminate\Database\Seeder;

class IPFilterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('ip_filters')->truncate();

        \DB::table('ip_filters')->insert(
            [
                [
                    'ip' => '72.255.55.2'
                ],
                [
                    'ip' => '72.255.55.1'
                ],
                [
                    'ip' => '72.255.55.3'
                ]
            ]
        );
    }
}
