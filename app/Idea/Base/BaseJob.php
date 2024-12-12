<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Base;

use App\Jobs\Job;
use Carbon\Carbon;

/**
 * Eloquent Model Idea class
 */
abstract class BaseJob extends Job
{
    public $params = [];
    public $delay = '';

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array $attributes
     * @param  string $time
     */
    public function __construct(array $params = [], $time = '0')
    {
        $this->params = $params;
        $this->delay = $this->timeConvertIntoCarbonObject($time);
    }

    /**
     * string convert into carbon object
     * @param  request $request
     * @return Carbon $date
     */
    private function timeConvertIntoCarbonObject($time)
    {
        $exploadedTime = explode(':', $time);
        $now = Carbon::now();

        // add days
        $now->addDays(head($exploadedTime));

        // add hours
        if (count($exploadedTime) == 2) {
            $now->addHours($exploadedTime[1]);
        }

        // add minuts
        if (count($exploadedTime) == 3) {
            $now->addMinutes($exploadedTime[2]);
        }

        // add seconds
        if (count($exploadedTime) == 4) {
            $now->addSeconds($exploadedTime[3]);
        }

        return $now;
    }
}
