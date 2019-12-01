<?php

namespace App\Http\Middleware;

use App\Consts;
use App\Http\Requests\RoleService;
use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class CheckPermissionRole
{
    private $roleService;
    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->path();
        $permissionPaths = $this->roleService->getAllPermission();
        if ($this->checkPermission($permissionPaths, $path) || $this->checkPermission(collect(Consts::$API_COMMON), $path)) {
            return $next($request);
        } else {
            throw new UnauthorizedException('Unauthorized api '.$path);
        }
    }

    private function checkPermission($permissionPaths, $path)
    {
        foreach ($permissionPaths as $permissionPath) {
            if ($permissionPath !== '' && Str::contains($path, $permissionPath)) {
                return true;
            }
        }
        return false;
    }
}
