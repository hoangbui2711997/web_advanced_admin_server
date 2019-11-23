<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Exceptions\AttributeProvidedInvalidException;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function addRole(Request $request)
    {
        if ($request->has('name')) {
            $role = new Role([
                'name' => $request->input('name', '')
            ]);
            $role->save();
            return 'Add role success';
        }
        throw new AttributeProvidedInvalidException();
    }

    public function editRole(Request $request)
    {
        if ($request->has(['name', 'id'])) {
            $role = Role::where('id', $request->input('id'))
                ->update(['name' => $request->input('name')]);
            return $role;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function delRole(Request $request)
    {
        try {
            DB::beginTransaction();

            if ($request->has('id')) {
                RolePermission::where('role_id', $request->input('id'))->delete();
                UserRole::where('role_id', $request->input('id'))->delete();
                Role::where('id', $request->input('id'))->delete();
                DB::commit();
                return 'Delete success fully';
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception);
            throw $exception;
        }
    }

    public function getRolePermissions () {
        $result = [];
        $rolePermissions = RolePermission::all()->groupBy('role_id')->values();
        foreach ($rolePermissions as $rolePermission) {
            foreach ($rolePermission as $permission) {
                $roleCode = $permission->role->name;
                $permissionName = $permission->permission->name;
                $viewName = $permission->name;

                $result[$roleCode][$permissionName][$viewName] = [
                    'checked' => $permission->checked,
                    'url' => $permission->url
                ];
            }
        }

        return $result;
    }

    public function editRermissions (Request $request) {
        if (!$request->has(['permissions', 'role_id'])) {
            throw new AttributeProvidedInvalidException();
        }

        $permissions = $request->input('permissions');
        $roleId = $request->input('role_id');
        $idCodePermissions = Permission::all(['id', 'name'])->keyBy(function ($value) {
            return $value['name'];
        });
        foreach ($permissions as $menu => $permissionMenus) {
            foreach ($permissionMenus as $permissionMenu => $values) {
                $checked = data_get($values, 'checked', false);
                $path = data_get($values, 'path', '');
                RolePermission::updateOrCreate(
                    [
                        'role_id' => $roleId,
                        'permission_id' => data_get($idCodePermissions[$menu], 'id', ''),
                        'name' => $permissionMenu,
                        'url' => $path
                    ],
                    [
                        'checked' => $checked
                    ]
                );
            }
        }
        return 'Save successful';
    }
}
