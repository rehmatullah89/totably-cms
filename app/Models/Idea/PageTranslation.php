<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/15/2017
 * Time: 3:21 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseTranslationModel;

class PageTranslation extends BaseTranslationModel
{
    public $timestamps = false;
    protected $fillable = ['title','body'];
}
