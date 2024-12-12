<?php

namespace App\Models\Idea;

use App\Idea\Base\BaseTranslationModel;

class RoleTranslation extends BaseTranslationModel
{
    public $timestamps = false;
    protected $fillable = ['title'];
}
