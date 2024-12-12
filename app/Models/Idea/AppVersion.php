<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Idea;

/**
 * Description of AppVersion
 *
 * @author Muhammad Abid
 */
use App\Idea\Base\BaseModel;

class AppVersion extends BaseModel
{
    protected $fillable = ['version', 'device_type', 'update_type', 'active'];
    protected $table = 'app_versions';
}
