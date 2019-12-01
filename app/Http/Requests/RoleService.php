<?php


namespace App\Http\Requests;


use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class RoleService
{
    public function getAllPermission()
    {
        if (Auth::id() === null) {
            return new UnauthorizedException();
        }
        $roleId = UserRole::where('user_id', Auth::id())->pluck('role_id')->toArray();
        $permissionIds = RolePermission::whereIn('role_id', $roleId)->where('checked', 1)->select('permission_id')->distinct()->get()->pluck('permission_id');
        Log::warning('$permissionIds');
        Log::warning($permissionIds);
        return Permission::whereIn('id', $permissionIds)->pluck('path');
    }
}