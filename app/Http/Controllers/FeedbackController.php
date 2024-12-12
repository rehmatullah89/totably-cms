<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/16/2017
 * Time: 3:36 PM
 */

namespace App\Http\Controllers;

use App\Idea\Base\BaseController;
use App\Repositories\FeedbackRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends BaseController
{
    protected $permissions = [
        "index"      => ["code" => "feedback", "action" => "read"],
        "id"     => ["code" => "feedback", "action" => "read"],
        "feedbackByUserId" => ["code" => "feedback", "action" => "read"],
    ];

    protected $feedbackRepository;

    /**
     * @param FeedbackRepository $feedbackRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(FeedbackRepository $feedbackRepository, Request $request)
    {
        parent::__construct($request);
        $this->feedbackRepository = $feedbackRepository;
    }

    /**
     * Validation Rules
     */
    public static function validationRules()
    {
        return [];
    }

    /**
     * Function to return all feedbacks
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->successData($this->feedbackRepository->findAll());
    }

    /**
     * Function to return respective feedback
     *
     * @return JsonResponse
     */
    public function one()
    {
        return $this->successData($this->feedbackRepository->findOne());
    }

    /**
     * Function to return all feedbacks by user
     *
     * @return JsonResponse
     */
    public function feedbackByUserId()
    {
        return $this->successData($this->feedbackRepository->findAllByUserId());
    }
}
