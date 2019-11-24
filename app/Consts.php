<?php

namespace App;

class Consts
{
    const QUEUE_SOCKET = 'socket';

    const CONNECTION_SOCKET = 'sync';

    const TRUE = 1;
    const FALSE = 0;

    const DEFAULT_PER_PAGE = 10;

    const ENV_TESTING = 'testing';

    const SUPPORTED_LOCALES = ['en', 'ja'];

    const DEFAULT_USER_LOCALE = 'en';

    const DEFAULT_TIMEZONE = 'Asia/Seoul';

    const COOKIE_USER_DEVICE = 'user_device_identify';

    const ADMIN_QUEUE = 'admin';

    const ALL = 'all';

    public static $ROLE_ADMIN = 1;
    public static $ROLE_MANAGER = 2;
    public static $ROLE_EMPLOYEE = 3;
    public static $ROLE_USER = 4;

    public static $ADMIN = 'ADMIN';
    public static $MANAGER = 'MANAGER';
    public static $EMPLOYEE = 'EMPLOYEE';
    public static $USER = 'USER';

}
