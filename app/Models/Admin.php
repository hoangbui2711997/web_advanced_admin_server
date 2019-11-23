<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\HasApiTokens;
use PHPGangsta_GoogleAuthenticator;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    const OTP_CACHE_LIVE_TIME = 0.5; // 30s
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email', 'password', 'google_authentication'
    ];

    /**
     * The attributes that should be hidde n for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function verifyOtp($authenticationCode)
    {
        $googleAuthenticator = new PHPGangsta_GoogleAuthenticator();
        $result = $googleAuthenticator->verifyCode($this->google_authentication, $authenticationCode, 0);

        if ($result) {
            $key = "OTPCode:{$this->id}:{$authenticationCode}";
            if (Cache::get($key) !== $authenticationCode) {
                Cache::put($key, $authenticationCode, Admin::OTP_CACHE_LIVE_TIME);
            }
        }
        return $result;
    }
}
