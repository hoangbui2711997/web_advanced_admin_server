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

    public static $API_COMMON = [
        'api/common/get-current-user',
        'api/common/user',
        'api/common/list'
    ];

    public static $UPLOAD_PATH = '/public/';
    public static $VERBS = ['POST', 'PUT', 'DELETE', 'GET'];
    public static $POST = 'POST';
    public static $PUT = 'PUT';
    public static $DELETE = 'DELETE';
    public static $GET = 'GET|HEAD';

    public static $VARIATION_TYPE_SMALL = 'small';
    public static $VARIATION_TYPE_DELUXE = 'medium';
    public static $VARIATION_TYPE_PREMIUM = 'premium';

    public static $COLLECTION_SIZES = ['standard', 'deluxe', 'premium'];
    public static $COLLECTION_VASE_SIZES = ['small', 'medium', 'large'];
    public static $COLLECTION_QUANTITY = [1, 2, 3];

    public static $COLLECTION_EXTRAS = ['mylar balloons', 'stuffed animal', 'chocolates'];

    public static $DECORATIONS = [
        'products' => 'name',
        'product_variations' => 'color',
        'product_extra_variations' => 'amount',
        'vases' => 'name',
        'vase_variations' => 'size',
    ];

    public static $INVOICE_STATUS_CREATED = 'created';
    public static $INVOICE_STATUS_ORDERING = 'ordering';
    public static $INVOICE_STATUS_NEED_PAY = 'need_pay';
    public static $INVOICE_STATUS_CANCELED = 'canceled';
    public static $INVOICE_STATUS_SUCCESS = 'success';

    public static $INVOICE_STATUSES = [
        'created',
        'ordering',
        'need_pay',
        'canceled',
        'success',
    ];
}
