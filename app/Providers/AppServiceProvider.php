<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\ImportedFile;
use App\Utils\BigNumber;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            $dataVersion = 0;
            $view->with('dataVersion', $dataVersion)->with('userLocale', App::getLocale());
        });
        Validator::extend('greater_than', static function ($attribute, $value, $otherValue = []) {
            $compareTo = empty($otherValue) ? 0 : $otherValue[0];
            $bigNumber = new BigNumber($value);
            return $bigNumber->comp($compareTo) > 0;
        });
        Validator::extend('uppercase', function ($attribute, $value) {
            return ctype_upper($value);
        });
        Validator::extend('user_exists', static function ($attribute, $email) {
            return DB::table('users')->where('email', strtolower($email))->where('status', 'active')->where('is_banned', 0)->exists();
        });
        DB::enableQueryLog();
        DB::listen(function ($query) {
            $ignoreKyes = [
                'insert into `jobs`',
                'select * from `jobs`',
                'insert into `admin_jobs`',
                'select * from `admin_jobs`',
            ];
            foreach ($ignoreKyes as $key) {
                if (substr($query->sql, 0, strlen($key)) === $key) {
                    return;
                }
            }
            Log::debug('SQL', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'runtime' => $query->time
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
