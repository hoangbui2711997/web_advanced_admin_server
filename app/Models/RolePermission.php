<?php

namespace App\Models;

use App\Custom\CustomModel;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends CustomModel
{
    protected $fillable = [
        'role_id',
        'permission_id',
        'name',
        'url',
        'checked',
    ];

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
