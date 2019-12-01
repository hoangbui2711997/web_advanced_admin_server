<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Exceptions\AttributeProvidedInvalidException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\RolePermissionControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function addPermission(Request $request)
    {
        if ($request->has('name')) {
            $category = new Permission([
                'name' => $request->input('name', '')
            ]);
            $category->save();
            return $category;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function editPermission(Request $request)
    {
        if ($request->has(['name', 'id'])) {
            $category = Permission::where('id', $request->input('id'))
                ->update(['name' => $request->input('name')]);
            return $category;
        }
        throw new AttributeProvidedInvalidException();
    }

    public function getPermissions(Request $request)
    {
        $limit = $request->input('limit', Consts::DEFAULT_PER_PAGE);

        return Permission::paginate($limit);
    }

    public function delPermission(Request $request)
    {
        if ($request->has('id')) {
            Permission::where('id', $request->input('id'))->delete();
            return 'ok';
        }
    }

    public function updatePermissionControl(Request $request)
    {
        ['role_id' => $roleId, 'permission_name' => $permissionName, 'role_permission_name' => $rolePermissionName, 'controls' => $controls] =
            $request->only(['role_id', 'permission_name', 'role_permission_name', 'controls']);
        try {
            DB::beginTransaction();
            $permissionId = Permission::where('name', $permissionName)->firstOrFail()->id;
            $rolePermission = RolePermission::where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->where('name', $rolePermissionName)
                ->firstOrFail();
            Log::warning($rolePermission->id);
            collect($controls)->each(function ($control) use ($rolePermission) {
                RolePermissionControl::updateOrInsert(
                    [
                        'role_permission_id' => $rolePermission->id,
                        'name' => $control['name'],
                    ],
                    [
                        'checked' => $control['checked']
                    ]);
            });
            DB::commit();
            return 'Update control success';
        } catch (\Exception $exception) {
            Log::warning($exception);
            DB::rollBack();
        }
//        RolePermissionControl::updateOrInsert();
    }
}
