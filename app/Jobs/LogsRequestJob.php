<?php

namespace App\Jobs;

use App\Idea\Base\BaseJob;
use App\Models\Idea\LogsRequest;
use Auth;

//insert the all data into logsRequest
class LogsRequestJob extends BaseJob
{

    /**
     * Insert logs data
     *
     * @return void
     */
    public function handle()
    {
        $new = new LogsRequest();

        $new->fill($this->params);

        $new->save();
    }
}
