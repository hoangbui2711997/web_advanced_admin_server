<?php

namespace App\Models;

use App\Custom\CustomModel;
use Illuminate\Database\Eloquent\Model;

class UserRole extends CustomModel
{
    //
    protected $table = 'user_roles';
    protected $fillable = [
      'user_id',
      'role_id'
    ];
}
