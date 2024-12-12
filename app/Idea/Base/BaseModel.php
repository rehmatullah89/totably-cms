<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent Model Idea class
 */
abstract class BaseModel extends Model
{

    public $hideTimestamp = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Hides attributes of the Eloquent model instance.
     *
     * @param array $attrs
     */
    public function hideAttributes(array $attrs)
    {
        foreach ($attrs as $attr) {
            $this->hidden[] = $attr;
        }
    }

    /**
     * Reveals attributes of the Eloquent model instance.
     *
     * @param array $attrs
     */
    public function revealAttributes(array $attrs)
    {
        $this->hidden = array_diff($this->hidden, $attrs);
    }

    /**
     * Protects attributes of the Eloquent model instance.
     *
     * @param array $attrs
     */
    public function guardAttributes(array $attrs)
    {
        $this->fillable = array_diff($this->fillable, $attrs); //fillable has its attributes minus what I am guarding
        foreach ($attrs as $attr) {
            $this->guarded[] = $attr;
        }
    }

    /**
     * removes attribute from the protection array
     *
     * @param array $attrs
     */
    public function unguardAttributes(array $attrs)
    {
        $this->guarded = array_diff($this->guarded, $attrs);
        foreach ($attrs as $attr) {
            $this->fillable[] = $attr; //add it to fillable since I can fill it now
        }
    }

    /**
     * Get the hidden attributes for the model.
     *
     * @return array
     */
    public function getHidden()
    {
        if ($this->hideTimestamp) {
            return array_merge($this->hidden, ['created_at', 'updated_at']);
        }

        return $this->hidden;
    }

    /**
     * Description: The following method returns the images respective model.
     * @author Muhammad Abid
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, 'model_id');
    }
}
