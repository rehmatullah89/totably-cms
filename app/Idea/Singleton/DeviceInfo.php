<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Singleton;

//this is a bad practice, and I am sure it's not working as expected
//please chech how it's used, shuja has some info about potential security issue because of this
class DeviceInfo
{
    public $device;
    protected $values = array();

    public function setDevice($device)
    {
        $this->device = $device;

        return $this;
    }

    public function __get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        return null;
    }

    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }
}
