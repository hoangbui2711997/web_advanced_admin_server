<?php

namespace App\Models;

use App\Custom\CustomModel;
use Illuminate\Database\Eloquent\Model;

class RolePermissionControl extends CustomModel
{
    //
    protected $fillable = [
        'role_permission_id',
        'checked',
        'name',
    ];
}
