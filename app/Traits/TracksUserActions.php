<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait TracksUserActions
{
    protected static function bootTracksUserActions()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
                if (in_array(SoftDeletes::class, class_uses_recursive($model))) {
                    $model->saveQuietly();
                }
            }
        });
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
