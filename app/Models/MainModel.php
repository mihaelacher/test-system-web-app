<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

abstract class MainModel extends Model
{
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->created_by = Auth::user()->id;
            }

            foreach ($model->toArray() as $name => $value) {
                if (!isset($value) || $value === '') {
                    $model->{$name} = null;
                }
            }
            return true;
        });
    }
}
