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



Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function () {
    Route::get('user', 'AdminController@getUser');
    Route::get('users', 'AdminController@getUsers');
    Route::post('update-user-role', 'AdminController@updateUserRole');
    Route::put('user', 'AdminController@updateUser');
    Route::post('user', 'AdminController@signup');
    Route::delete('user', 'AdminController@delUser');
    Route::get('employees', 'AdminController@getEmployees');

    Route::get('get-current-user', 'AdminController@getCurrentUser');

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

    Route::group(['prefix' => 'permission'], function () {
        Route::get('list', 'PermissionController@getPermissions');
        Route::put('edit', 'PermissionController@editPermission');
        Route::delete('del', 'PermissionController@delPermission');
        Route::post('add', 'PermissionController@addPermission');
    });

    Route::group(['prefix' => 'role'], function () {
        Route::get('list', 'RoleController@getRoles');
        Route::get('role-permissions', 'RoleController@getRolePermissions');
        Route::post('add', 'RoleController@addRole');
        Route::post('permissions', 'RoleController@editRermissions');
        Route::put('edit', 'RoleController@editRole');
        Route::post('del', 'RoleController@delRole');
    });
});

Route::middleware(['auth:api'])->group(function () {

});
