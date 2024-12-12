<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Idea;

/**
 * Description of LogsRequest
 *
 * @author Muhammad Abid
 */
use App\Idea\Base\BaseModel;

class LogsRequest extends BaseModel
{
    protected $fillable = ['role', 'device_type', 'country', 'method', 'url', 'json', 'data'];
    protected $table = 'logs_requests';
}
