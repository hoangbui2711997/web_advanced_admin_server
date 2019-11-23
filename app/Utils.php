<?php

namespace App;

use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Services\UserService;
use Carbon\Carbon;
use App\Consts;
use App\Utils\BigNumber;
use Auth;
use DB;
use App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Mail;
use App\Mail\DividendNotification;

class Utils
{
    public static function makeQrCode($text)
    {
        // check if the qr code already exist then return it
        // otherwise, generate new one and return
        $path = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
            . "qr_codes" . DIRECTORY_SEPARATOR;

        $filename = $path . $text . ".png";
        if (!file_exists($filename)) {
            QrCode::format('png')->size(220)->generate($text, $filename);
        }

        return "/storage/qr_codes/". $text .".png";
    }
    public static function makeQrCodeRefferal($text)
    {
        // check if the qr code already exist then return it
        // otherwise, generate new one and return
        $path = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
            . "qr_codes" . DIRECTORY_SEPARATOR;
        $name='user_ref_code_'.substr($text, strpos($text, '=')+1) ;
        $filename = $path . $name . ".png";
        if (!file_exists($filename)) {
            QrCode::format('png')->size(500)->generate($text, $filename);
        }

        return "/storage/qr_codes/". $name .".png";
    }

    public static function isEqual($a, $b)
    {
        return abs($a - $b) < 1e-10;
    }

    public static function todaySeoul() {
        return Carbon::today('Asia/Seoul');
    }

    public static function previous24hInMillis() {
        return Carbon::now()->subDay()->timestamp * 1000;
    }

    public static function previousDayInMillis($day) {
        return Carbon::now()->subDay($day)->timestamp * 1000;
    }

    public static function getStartOfDayInMillis()
    {
        return Carbon::now()->startOfDay()->timestamp * 1000;
    }

    public static function currentMilliseconds() {
        return round(microtime(true) * 1000);
    }

    public static function formatVndAmount($amount) {
        return number_format(abs($amount));
    }

    public static function millisecondsToDateTime($timestamp, $timezoneOffsetInMins, $format) {
        return Utils::millisecondsToCarbon($timestamp, $timezoneOffsetInMins)->format($format);
    }

    public static function millisecondsToCarbon($timestamp, $timezoneOffsetInMins) {
        return Carbon::createFromTimestampUTC(floor($timestamp/1000))->subMinutes($timezoneOffsetInMins);
    }

    public static function dateTimeToMilliseconds($stringDate) {
        $date = !empty($stringDate) ? Carbon::parse($stringDate) : Carbon::now();
        return $date->timestamp * 1000 + $date->micro;
    }

    public static function mulBigNumber($number1, $number2) {
        if (!$number1 || !$number2) return "0";
        return (new BigNumber($number1))->mul($number2)->toString();
    }


    public static function getTriggerConditionStatus($condition) {
        if ($condition == 'le') return '<=';
        if ($condition == 'ge') return '>=';
        return '';
    }

    public static function trimFloatNumber($val) {
        return strpos($val,'.')!==false ? rtrim(rtrim($val,'0'),'.') : $val;
    }

    public static function saveFileToStorage($file, $pathFolder, $prefixName = null, $driver = 'application')
    {
        $filename = Utils::currentMilliseconds() . '.' . $file->getClientOriginalExtension();
        if (!empty($prefixName)) {
            $filename = $prefixName . '_' .$filename;
        }
        $filename = "{$pathFolder}/{$filename}";
        Storage::disk(env('FILESYSTEM_DRIVER', $driver))->put($filename, file_get_contents($file));
        return Storage::disk(env('FILESYSTEM_DRIVER', $driver))->url($filename);
    }

    /**
     * Escape special characters for a LIKE query.
     *
     * @param string $value
     * @param string $char
     *
     * @return string
     */
    public static function escapeLike(string $value, string $char = '\\'): string
    {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }

    public static function getFileName($fileName, $fileExtension = '.csv')
    {
        return $fileName . '-' . Carbon::now()->format('Y-m-d') . $fileExtension ;
    }

    public static function getTomorrowDay()
    {
        return Carbon::now()->addDays(1);
    }

    public static function removeZeroLeading($value)
    {
        if (Str::contains($value, '.')) {
            $values = preg_split('/[.]/', $value);
            $x1 = $values[0];
            $x2 = rtrim($values[1], '0');
            $x2 = $x2 == '' ? '' : ".$x2";
            return $x1.$x2;
        } else {
            return $value;
        }
    }
}
