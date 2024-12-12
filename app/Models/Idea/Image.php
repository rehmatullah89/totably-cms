<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Idea;

/**
 * Description of Image
 *
 * @author Muhammad Abid
 */
use App\Idea\Base\BaseModel;

class Image extends BaseModel
{
    protected $fillable = ['image', 'model_id'];
    protected $table = 'images';
}
