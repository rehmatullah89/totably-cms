<?php


namespace App\Repositories;

use App\Models\Idea\Feedbacks;
use App\Idea\Types\ExceptionType;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to feedbacks
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class FeedbackRepository
{
    use ExceptionType;

    protected $feedback;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Feedbacks $feedback, Request $request)
    {
        $this->request = $request;
        $this->feedback = $feedback;
    }

    /**
     * Description: This function will return all feedbacks
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Feedbacks
     */
    public function findAll()
    {
        $feedbacks = $this->feedback::all();
        if (!$feedbacks) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $feedbacks;
    }

    /**
     * Description: This function will return one feedback
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Feedbacks
     */
    public function findOne()
    {
        $feedback = $this->feedback::byId(request('id'))->first();
        if (!$feedback) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $feedback;
    }

    /**
     * Description: This function will return all feedbacks by user
     * @author Hassan Mehmood - I2L
     * @return \App\Models\Idea\Feedbacks
     */
    public function findAllByUserId()
    {
        $feedbacks = $this->feedback::byUserId(request('user_id'))->get();
        if (! $feedbacks->count()) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $feedbacks;
    }
}
