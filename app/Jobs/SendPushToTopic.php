<?php

namespace App\Jobs;

use App\Idea\Base\BaseJob;
use Illuminate\Support\Facades\Log;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;

class SendPushToTopic extends BaseJob
{
    /**
     * Send the email.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Sending Push To Notification[[' . $this->params['body'] . ']] to ' . $this->params['topic']);

            //create the notification
            $notificationBuilder = new PayloadNotificationBuilder(config('main.title'));
            $notificationBuilder->setBody($this->params['body'])->setSound('default');

            //build notication and option
            $notification = $notificationBuilder->build();

            //send the notification
            $topic = new Topics();
            $topic->topic($this->params['topic']);

            \FCM::sendToTopic($topic, null, $notification, null);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            throw $e;
        }
    }
}
