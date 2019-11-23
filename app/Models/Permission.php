<?php

namespace App\Models;

use App\Custom\CustomModel;

class Permission extends CustomModel
{
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
