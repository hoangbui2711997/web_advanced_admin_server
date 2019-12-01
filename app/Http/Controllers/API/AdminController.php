<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Product;
use App\Models\User;
use App\Models\UserRole;
use App\Services\CommonService;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class AdminController extends Controller
{
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

    public function getNavigators()
    {
        return $this->commonService->getNavigators();
    }

    public function getCategories()
    {
        return $this->commonService->getCategories();
    }

    private $commonService;
    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function showAPIs__()
    {
        Artisan::call('route:api');
        // Setup the file descriptors
        $descriptors = [
            0 => ['pipe', 'w'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        // Start the script
        $proc = proc_open('php artisan route:api', $descriptors, $pipes, '/home/jetly/backup-1/web_advanced_server');

        // Read the stdin
        $stdin = stream_get_contents($pipes[0]);
        fclose($pipes[0]);

        // Read the stdout
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Read the stderr
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        Log::warning('$stdin');
        Log::warning($stdin);
        Log::warning('$stdout');
        Log::warning($stdout);
        Log::warning('$stderr');
        Log::warning($stderr);

        // Close the script and get the return code
        $return_code = proc_close($proc);

        dd(json_decode($stdout));
//		$stdin = stream_get_contents($pipes[0]);
//		fclose($pipes[0]);
//		Log::warning(Artisan::output());
//		dd(Artisan::output());
        return Artisan::output();
    }

    public function showAPIs()
    {
        exec("cd " . base_path() . " && php artisan route:list | awk '/GET|HEAD|POST|PUT|DELETE/' | awk '/App\\\\Http\\\\Controllers\\\\API/'", $apis);
        $address = [];
        $result = [];
        $keyAddress = 3;
        Log::warning($apis);
        foreach ($apis as $api) {
            if (!empty($api)) {
                $address[] = [
                    'verb' => $verb = implode(Utils::getVerb($api), ','),
                    'url' => env('APP_URL')
                        . '/'
                        . trim(explode('|', $api)
                        [Str::contains($verb, 'GET|HEAD') ? $keyAddress + 1 : $keyAddress])
                ];
            }
        }

        data_set($result, 'address', $address);
        data_set($result, 'apis', $apis);

        return $result;
    }
}
