<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class UserManagementController extends Controller
{
    public function getUsers(Request $request)
    {
        $limit = $request->input('limit', 10);
        return response()->json(
            User::whereHas('roles', function ($query) {
                $query->where('roles.name', Consts::$USER);
            })
                ->with('roles')
                ->paginate($limit));
    }

    public function getUser(Request $request)
    {
        if (Auth::id() === null) {
            throw new UnauthorizedException();
        }
        return response()->json(
            User::join('user_roles', 'user_roles.user_id', 'users.id')
                ->join('roles', 'user_roles.role_id', 'roles.id')
                ->join(DB::raw('(select * from role_permissions where role_permissions.checked = 1) as role_permissions'), 'role_permissions.role_id', 'roles.id')
                ->join('permissions', 'role_permissions.permission_id', 'permissions.id')
                ->leftJoin(
                    DB::raw('(select * from role_permission_controls where role_permission_controls.checked = 1) as role_permission_controls'),
                    'role_permissions.id',
                    'role_permission_controls.role_permission_id'
                )
                ->where('users.id', Auth::id())
                ->selectRaw('permissions.name as menu, role_permissions.name as page, role_permissions.path as link, role_permission_controls.name as control')
                ->distinct()
                ->get()
        );
    }

    public function updateUser(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string'
//				'remember_me' => 'boolean'
            ]);

            User::find($request->input('id'))->update([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'name' => $request->name
            ]);

            return 'update succeed';
        } catch (\Exception $ex) {
            Log::info($ex);
            throw $ex;
        }
    }

    public function delUser(Request $request)
    {
        try {
            $id = $request->input('user_id');
            User::destroy($id);
            return ['message' => 'delete succeeded'];
        } catch (\Exception $ex) {
            Log::info($ex);
            throw $ex;
        }
    }

    public function getEmployees(Request $request)
    {
        $limit = $request->input('limit', 10);
        return response()->json(
            User::whereHas('roles', function ($query) {
                $query->where('roles.name', '<>', Consts::$USER)
                      ->where('roles.name', '<>', Consts::$ADMIN);
            })
            ->with('roles')
            ->paginate($limit)
        );
    }

    public function updateUserRole(Request $request)
    {
        $userId = $request->input('id');
        $roleIds = $request->input('roleChecks');
        if (!empty($roleIds) && $userId !== null) {
            $filteredRoleIds = collect($roleIds)->filter(function ($value, $key) {
                return $value === true;
            });
            try {
                DB::beginTransaction();
                UserRole::where('user_id', $userId)->delete();
                $filteredRoleIds->each(function ($value, $key) use ($userId) {
                    UserRole::updateOrInsert([
                        'role_id' => $key,
                        'user_id' => $userId
                    ]);
                });

                $keyFilteredRoleIds = $filteredRoleIds->keys()->toArray();
                UserRole::where('user_id', $userId)->whereNotIn('role_id', $keyFilteredRoleIds)->delete();
                DB::commit();
                return 'Update success';
            } catch (\Exception $exception) {
                Log::info($exception);
                DB::rollBack();
            }
        }
    }

    public function getCurrentUser()
    {
        return Auth::user();
    }

    public function getRoles()
    {
        return Role::all();
    }
}
