<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Idea;

/**
 * Description of IPFilter
 *
 * @author Muhammad Abid
 */
use App\Idea\Base\BaseModel;

class IPFilter extends BaseModel
{
    protected $fillable = ['ip', 'status'];
    protected $table = 'ip_filters';
}
