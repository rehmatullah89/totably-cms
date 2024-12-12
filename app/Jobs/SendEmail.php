<?php

namespace App\Jobs;

use App\Idea\Base\BaseJob;
use Illuminate\Support\Facades\Mail;
use Log;

class SendEmail extends BaseJob
{
    /**
     * Send the email.
     *
     * @return void
     */
    public function handle()
    {
        //specify the sent from
        $this->params['from'] = [
            'name'  => env('MAIL_FROM_NAME', 'IdeaToLife Testing'),
            'email' => env('MAIL_FROM_ADDRESS', 'youssef.jradeh@ideatolife.me')
        ];

        //if we are notifying the site owner
        if (! empty($this->params['sendToOwner'])) {
            $this->params['to'] = $this->params['from'];
        }

        //checking the receiver
        if (empty($this->params['to']) || empty($this->params['to']['email'])) {
            Log::info('invalid email in sendEmail job ', $this->params);

            return false;
        }

        //checking the name of the receiver
        $this->params['to']['name'] = ! empty($this->params['to']['name']) ?
            $this->params['to']['name']
            : $this->params['to']['email'];

        //send the actual email
        Mail::send(
            $this->params['template'],
            $this->params,
            function ($message) {
                $message->setFrom($this->params['from']['email'], $this->params['from']['name']);
                $message->setTo($this->params['to']['email'], $this->params['to']['name']);

                if (! empty($this->params['subject'])) {
                    $message->subject($this->params['subject']);
                }
            }
        );
    }
}
