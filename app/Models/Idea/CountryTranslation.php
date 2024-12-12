<?php
/**
 * Created by PhpStorm.
 * User: Abed Bilani
 * Date: 6/7/2017
 * Time: 12:56 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseTranslationModel;

class CountryTranslation extends BaseTranslationModel
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
