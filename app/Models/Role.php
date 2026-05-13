<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'description', 'status', 'created_by', 'updated_by', 'deleted_at'])]
#[Hidden(['deleted_at', 'updated_at', 'created_at'])]
class Role extends Model
{
    use SoftDeletes;

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }
}
