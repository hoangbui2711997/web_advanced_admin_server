<?php

namespace App\Http\Controllers\Auth;

use App\Consts;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);
        $user = new User([
            'name' => '',
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => '',
        ]);
        try {
            DB::beginTransaction();
            $user->save();

            DB::table('user_roles')->insert([
                [
                    'role_id' => Consts::$ROLE_EMPLOYEE,
                    'user_id' => $user->id,
                ]
            ]);

            DB::commit();
            return response()->json([
                'data' => [
                    'message' => 'Successfully created user!'
                ]
            ], 201);
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
            throw $exception;
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:190|unique_email',
            'password' => 'required|string|min:8|max:72|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed|password_white_space',
        ]);
    }
}
