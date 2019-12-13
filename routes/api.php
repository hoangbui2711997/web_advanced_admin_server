<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These#
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('common/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'common'], function () {
        Route::get('get-current-user', 'UserManagementController@getCurrentUser');
        Route::get('user', 'UserManagementController@getUser');
        Route::get('list', 'RoleController@getRoles');
    });

    Route::group(['prefix' => 'user-management'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('users', 'UserManagementController@getUsers');
            Route::get('user', 'UserManagementController@getUser');
            Route::put('user', 'UserManagementController@updateUser');
            Route::delete('user', 'UserManagementController@delUser');
            Route::get('get-current-user', 'UserManagementController@getCurrentUser');
            Route::post('user', 'AdminController@signup');
        });
        Route::group(['prefix' => 'employee'], function () {
            Route::post('update-user-role', 'UserManagementController@updateUserRole');
            Route::put('user', 'UserManagementController@updateUser');
            Route::get('employees', 'UserManagementController@getEmployees');
            Route::delete('user', 'UserManagementController@delUser');
            Route::get('list', 'UserManagementController@getRoles');
            Route::post('user', 'AdminController@signup');
        });
    });

    Route::group(['prefix' => 'product-management'], function () {
        Route::group(['prefix' => 'product'], function () {
            Route::get('list', 'ProductController@getProducts');
            Route::get('detail', 'ProductController@getProduct');
            Route::post('edit', 'ProductController@editProduct');
            Route::post('del', 'ProductController@delProduct');
            Route::post('add', 'ProductController@addProduct');
            Route::get('discounts', 'ProductController@getDiscounts');
        });

        Route::group(['prefix' => 'category'], function () {
            Route::get('list', 'CategoryController@getCategories');
            Route::put('edit', 'CategoryController@editCategory');
            Route::delete('del', 'CategoryController@delCategory');
            Route::post('add', 'CategoryController@addCategory');
        });
    });

    Route::group(['prefix' => 'role-management'], function () {
        Route::group(['prefix' => 'role'], function () {
            Route::get('list', 'RoleController@getRoles');
            Route::get('role-permissions', 'RoleController@getRolePermissions');
            Route::post('add', 'RoleController@addRole');
            Route::post('permissions', 'RoleController@editRermissions');
            Route::put('edit', 'RoleController@editRole');
            Route::post('del', 'RoleController@delRole');
        });

        Route::group(['prefix' => 'permission'], function () {
            Route::get('list', 'PermissionController@getPermissions');
            Route::put('edit', 'PermissionController@editPermission');
            Route::delete('del', 'PermissionController@delPermission');
            Route::post('add', 'PermissionController@addPermission');
            Route::post('update-permission-control', 'PermissionController@updatePermissionControl');
        });
    });

    Route::group(['prefix' => 'chat-management'], function () {
        Route::group(['prefix' => 'waiting'], function () {
            Route::get('users', 'ChatController@getAllUserActive');
//            Route::get('messages', 'ChatController@getPreviewMessages');
            Route::get('preview-message', 'ChatController@getPreviewMessages');
            Route::post('pair', 'ChatController@pairWithUser');
        });
        Route::group(['prefix' => 'chat-room'], function () {
            Route::post('send-message', 'ChatController@sendMessage');
            Route::get('all-user-pairing', 'ChatController@getAllUserPairing');
            Route::get('messages', 'ChatController@getMessages');
            Route::post('un-pair', 'ChatController@unPairWithUser');
        });
    });


});
