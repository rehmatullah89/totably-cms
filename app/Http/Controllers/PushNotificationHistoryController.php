<?php
/**
 * Created by PhpStorm.
 * Push Notification History: Ideatolife
 * Date: 7/5/2017
 * Time: 3:07 PM
 */

namespace App\Http\Controllers;

use App\Idea\Base\BaseController;
use App\Idea\Types\NotificationType;
use App\Models\Idea\PushNotificationHistory;
use App\Repositories\PushNotificationHistoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushNotificationHistoryController extends BaseController
{
    use NotificationType;

    protected $permissions = [
        "index" => ["code" => "push_notifications", "action" => "read"],
        "destroy" => ["code" => "push_notifications", "action" => "write"],
        "store" => ["code" => "push_notifications", "action" => "write"],
        "update" => ["code" => "push_notifications", "action" => "write"],
        "updateAll" => ["code" => "push_notifications", "action" => "write"],
    ];

    protected $pushNotificationHistoryRepository;

    /**
     * @param PushNotificationHistoryRepository $pushNotificationHistoryRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(PushNotificationHistoryRepository $pushNotificationHistoryRepository, Request $request)
    {
        parent::__construct($request);
        $this->pushNotificationHistoryRepository = $pushNotificationHistoryRepository;
    }

    /**
     * Validation Rules
     */
    public static function validationRules()
    {
        return [
            'store' => [
                'target_id' => 'exists:users,id'
            ]
        ];
    }

    /**
     * Init
     */
    public function init()
    {
        $this->setModel(new PushNotificationHistory());
        $this->with = ['target'];
    }

    /**
     * Description: The following method will fetch all Push Notification Histories.
     * @return JsonResponse
     */
    public function index()
    {
        return $this->successData($this->pushNotificationHistoryRepository->findAll());
    }

    /**
     * Description: The following method will fetch one Push Notification History.
     *
     * @param int    : the admin id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->pushNotificationHistoryRepository->findOne($id));
    }

    /**
     * Description: The following method will add new admin to the system
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function store()
    {
        return $this->success($this->messages['save_success'], $this->pushNotificationHistoryRepository->savePushNotificationHistory());
    }

    /**
     * update a Model
     */
    public function update($id)
    {
        return $this->success($this->messages['save_success'], $this->pushNotificationHistoryRepository->updatePushNotificationHistory($id));
    }

    /**
     * Description: The following method will delete one Push Notification History.
     *
     * @param int    : id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->pushNotificationHistoryRepository->deleteNotification($id) ? $this->success() : $this->failed();
    }
}
