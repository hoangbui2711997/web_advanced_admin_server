<?php

namespace App\Models;

use App\Custom\CustomModel;
use Illuminate\Database\Eloquent\Model;

class Role extends CustomModel
{
//    protected $with = ['permissions'];
    protected $fillable = [
        'name'
    ];

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class)->with('controls');
    }
}
