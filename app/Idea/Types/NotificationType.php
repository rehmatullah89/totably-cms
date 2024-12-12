<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 7/5/2017
 * Time: 6:28 PM
 */

namespace App\Idea\Types;

use App\Jobs\SendPush;
use App\Models\Idea\Device;
use App\Models\Idea\UserNotifications;

trait NotificationType
{
    /**
     * @param $to
     * @param $message
     * @param $type
     *
     * @return array
     * Description: The following function send notification to Users
     */
    public function sendNotification($to, $message, $type, $group = false)
    {

        $notification          = new UserNotifications();
        $notification->user_id = 1;
        if (! $group) {
            $notification->target_id = $to;
        }
        $notification->desc = $message;
        $notification->type = $type;
        $notification->save();

        if (! $group) {
            sendPush($message, $to);
        } else {
            $data['message'] = $message;
            $data['message'] = $group;
            dispatch(new SendPush($data));
        }

        return array($notification);
    }
}
