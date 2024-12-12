<?php

use App\Models\Idea\Feedbacks;
use Illuminate\Database\Seeder;

class FeedbackTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * insert user feedback
     */
    public function run()
    {
        \DB::table('feedback')->truncate();
        $feedbacks = array(
            array('id' => 1, 'user_id' => '2', 'subject' => 'Test Feedback', 'body' => 'This is feedback'),
        );
        Feedbacks::insert($feedbacks);
    }
}
