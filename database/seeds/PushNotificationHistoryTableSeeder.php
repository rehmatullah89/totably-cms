<?php

use App\Models\Idea\PushNotificationHistory;
use Illuminate\Database\Seeder;

class PushNotificationHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * insert user push_notification_history
     */
    public function run()
    {
        \DB::table('push_notification_histories')->truncate();
        $push_notification_historys = array(
            array( 'target_id' => '2', 'text' => 'test notification', 'sent' => '1', 'push_date' => '2019-11-15 11:19:14'),
        );
        PushNotificationHistory::insert($push_notification_historys);
    }
}
