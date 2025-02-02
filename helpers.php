<?php

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

if (!function_exists('bitcastle_hash')) {
    // @codingStandardsIgnoreStart
    function bitcastle_hash($key = '')
    {
        $plain = $key . config('app.encryption_key', '');
        return sha1($plain);
    }
}

if (!function_exists('bitcastle_unique')) {
    // @codingStandardsIgnoreStart
    function bitcastle_unique()
    {
        $key = round(microtime(true) * 10000) . config('app.encryption_key', '');
        return sha1($key);
    }
}
if (!function_exists('guidv4')) {
    function guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
