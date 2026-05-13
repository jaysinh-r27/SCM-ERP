<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Helper
{
    public static function getPermissions()
    {
        if (app()->bound('permissions')) {
            return app('permissions');
        }

        $permissions = DB::table('roles')
            ->join('role_users', 'roles.id', '=', 'role_users.role_id')
            ->join('role_permissions', 'roles.id', '=', 'role_permissions.role_id')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_users.user_id', Auth::id())
            ->distinct()
            ->pluck('permissions.slug')
            ->toArray();

        app()->instance('permissions', $permissions);

        return $permissions;
    }
}
