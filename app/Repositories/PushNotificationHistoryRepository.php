<?php


namespace App\Repositories;

use App\Idea\Base\BasePaging;
use App\Jobs\SendPushToTopic;
use App\Models\Idea\Profile;
use App\Models\Idea\PushNotificationHistory;
use App\Models\Idea\PushNotificationTopic;
use App\Models\Idea\User;
use App\Models\Idea\UserNotifications;
use App\Idea\Types\ExceptionType;
use Illuminate\Http\Request;
use App\Idea\Types\NotificationType;

/**
 * Description: The following repository is used to handle all function related to notifications
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class PushNotificationHistoryRepository
{
    use ExceptionType, NotificationType;

    protected $pushNotificationHistory;

    /**
     * @var Request
     */
    private $request;

    public function __construct(PushNotificationHistory $pushNotificationHistory, Request $request)
    {
        $this->request = $request;
        $this->pushNotificationHistory = $pushNotificationHistory;
    }

    /**
     * Description: This function returns all push notifications
     * @author Hassan Mehmood - I2L
     * @return PushNotificationHistory
     */
    public function findAll()
    {
        $query = $this->pushNotificationHistory::with("target");
        $query = new BasePaging($query);
        return $query;
    }

    /**
     * Description: This function returns respected push notification
     * @author Hassan Mehmood - I2L
     * @return PushNotificationHistory
     */
    public function findOne($id)
    {
        return $this->pushNotificationHistory::with('target')->find($id);
    }

    /**
     * Description: This function will save push notifications
     * @return PushNotificationHistory
     * @throws \Exception
     * @author Hassan Mehmood - I2L
     */
    public function savePushNotificationHistory()
    {
        /*
         * If user don't send push date while inserting, we have written the following script
         * so code dont break
        */
        $push_date  = (isset($this->request->all()['push_date']) && $this->request->all()['push_date']) ? $this->request->all()['push_date'] : date('Y-m-d H:i:s');
        $this->request->merge(['push_date' => $push_date]);

        $text       = request("text");
        $userId     = request("target_id");

        $this->pushNotificationHistory->target_id = $userId;
        $this->pushNotificationHistory->text = $text;
        $this->pushNotificationHistory->devices = '';
        $this->pushNotificationHistory->sent = 0;
        $this->pushNotificationHistory->push_date = $push_date;
        if (!$this->pushNotificationHistory->save()) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        if ($userId && $userId != "null" && $userId != "all") {
            $user   = User::find($userId);
            if (!$user) {
                $this->pushNotificationHistory->delete();
                $this->raiseHttpResponseException('target_not_exist');
            }

            $this->sendNotification($user->id, $text, "notification");

            return $this->pushNotificationHistory;
        } elseif ($topic = request("devices")) {
            $this->sendNotificationToTopic($topic, $text);
        } else {
            //TODO : this is dangerous and it might need to be changed SOON
            $this->sendNotificationToTopic("all", $text);
        }

        //update sent value
        $this->pushNotificationHistory->sent = 1;
        $this->pushNotificationHistory->save();

        return $this->pushNotificationHistory;
    }

    /**
     * Description: This function will update push notifications
     * @return PushNotificationHistory
     * @throws \Exception
     * @author Hassan Mehmood - I2L
     */
    public function updatePushNotificationHistory($id)
    {
        if (!$id) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        /*
         * If user don't send push date while inserting, we have written the following script
         * so code dont break
        */
        $push_date  = (isset($this->request->all()['push_date']) && $this->request->all()['push_date']) ? $this->request->all()['push_date'] : date('Y-m-d H:i:s');
        $this->request->merge([
            'push_date' => $push_date,
        ]);

        $text       = request("text");
        $userId     = request("target_id");

        $this->pushNotificationHistory = $this->pushNotificationHistory::where('id', $id)->firstOrFail();
        $this->pushNotificationHistory->text = $text;
        if (!$this->pushNotificationHistory->save()) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        if ($userId && $userId != "null" && $userId != "all") {
            $user = User::find($userId);
            if (!$user) {
                $this->pushNotificationHistory->delete();

                $this->raiseHttpResponseException('idea::general.target_does_not_exist');
            }

            $this->sendNotification($user->id, $text, "notification");
            return $this->pushNotificationHistory;
        } elseif ($topic = request("devices")) {
            $this->sendNotificationToTopic($topic, $text);
        } else {
            //TODO : this is dangerous and it might need to be changed SOON
            $this->sendNotificationToTopic("all", $text);
        }

        //update sent value
        $this->pushNotificationHistory->sent = 1;
        $this->pushNotificationHistory->save();

        return $this->pushNotificationHistory;
    }

    /**
     * @param $topic
     * @param $message
     */
    public function sendNotificationToTopic($topic, $message)
    {
        $topicModel = PushNotificationTopic::byName($topic)->first();

        if (!$topicModel) {
            return;
        }

        $notification = new UserNotifications();
        $notification->user_id = $this->user->id;
        $notification->type = "notification_topic";
        $notification->desc = $message;
        $notification->save();

        //To be added later
        $data['parameters'] = [];
        $data['extraData'] = [];
        $data['topic'] = $topic;
        $data['body'] = $message;
        dispatch(new SendPushToTopic($data));
    }

    /**
     * Description: This function will delete respected push notification
     * @author Hassan Mehmood - I2L
     * @return boolean
     */
    public function deleteNotification($id)
    {
        $push_notification = $this->pushNotificationHistory::find($id);
        if (!$push_notification) {
            $this->raiseHttpResponseException('cannot_delete_profile');
        }

        return ($push_notification->delete()) ? true : false;
    }
}
