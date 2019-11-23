<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function getCurrentUser()
    {
       return  Auth::user();
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function getUsers(Request $request)
    {
        $limit = $request->input('limit', 10);
        return response()->json(
            User::join('user_roles', 'users.id', 'user_roles.user_id')
                ->join('roles', 'roles.id', 'user_roles.role_id')
                ->where('role_id', Consts::$ROLE_USER)
                ->select('users.*')
                ->distinct('users.id')
                ->with('roles')
                ->paginate($limit));
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => Str::random(60),
        ]);
        try {
            DB::beginTransaction();
            $user->save();
            $userRole = new UserRole([
                'user_id' => $user->id,
                'role_id' => $request->input('is_user', 0) > 0 ? Consts::$ROLE_USER : Consts::$ROLE_EMPLOYEE,
            ]);
            $userRole->save();
//        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
//        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
            DB::commit();
            return 'Successfully created user!';
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            throw $exception;
        }
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

    public function delUser(Request $request) {
        try {
            $id = $request->input('user_id');
            User::destroy($id);
            return ['message' => 'delete succeeded'];
        } catch (\Exception $ex) {
            Log::info($ex);
            throw $ex;
        }
    }

    private function isUser(): bool
    {
        return UserRole::where('user_id', Auth::id())
                ->where('role_id', Consts::$ROLE_USER)->count() > 0;
    }

    public function getEmployees(Request $request)
    {
        $limit = $request->input('limit', 10);
        return response()->json(
            User::join('user_roles', 'users.id', 'user_roles.user_id')
                ->join('roles', 'roles.id', 'user_roles.role_id')
                ->where('role_id', '<>', Consts::$ROLE_USER)
                ->where('role_id', '<>', Consts::$ROLE_ADMIN)
                ->select('users.*')
                ->distinct('users.id')
                ->with('roles')
                ->paginate($limit));
    }

    public function getPermissions(Request $request)
    {
        $limit = $request->input('limit', 10);
        return Permission::paginate($limit);
    }
}
