<?php

use App\Models\Idea\Profile;
use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('profiles')->truncate();
        $profile          = new Profile();
        $profile->user_id = 1;
        $profile->save();
    }
}
