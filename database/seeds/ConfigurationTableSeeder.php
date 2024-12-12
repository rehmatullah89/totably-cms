<?php

use App\Models\Idea\Configuration;

use Illuminate\Database\Seeder;

class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('configurations')->truncate();
        $configurations = array(
            array('id'=>'1' , 'value'=>'10000000' , 'code'=>'config_key' , 'text'=>'Config Name'),
        );
        Configuration::insert($configurations);
    }
}
