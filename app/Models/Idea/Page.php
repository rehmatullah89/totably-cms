<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/15/2017
 * Time: 3:21 PM
 */

namespace App\Models\Idea;

use App\Idea\Base\BaseModel;
use App\Idea\Types\TranslatableType;

class Page extends BaseModel
{
    use TranslatableType;
    public $translatedAttributes = ['title','body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')->with('children');
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
