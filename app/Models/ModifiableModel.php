<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

abstract class ModifiableModel extends MainModel
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $userId = Auth::user()->id;

            if ($userId && $model->created_by === null) {
                $model->created_by = $userId;
                $model->save();
            }
        });

        static::updating(function ($model) {
            $userId = Auth::user()->id;

            if ($userId) {
                $model->updated_by = $userId;
                $model->save();
            }
        });

        static::deleting(function ($model) {
            $userId = Auth::user()->id;

            if ($userId) {
                $model->updated_by = $userId;
                $model->deleted_by = $userId;
                $model->save();
            }
        });
    }
}
