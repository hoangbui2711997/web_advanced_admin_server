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
        'path',
        'checked',
    ];

    protected $casts = [
        'checked' => 'boolean'
    ];

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function permission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function controls(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RolePermissionControl::class);
    }
}
