<?php

use App\Models\Idea\Action;
use Illuminate\Database\Seeder;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('actions')->truncate();
        $actions = array(
            array('id'=>'1' , 'name'=>'read' ),
            array('id'=>'2' , 'name'=>'write' ),
        );
        Action::insert($actions);
    }
}
