<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class MainModel extends Model
{
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            foreach ($model->toArray() as $name => $value) {
                if (!isset($value) || $value === '') {
                    $model->{$name} = null;
                }
            }
            return true;
        });
    }
}
