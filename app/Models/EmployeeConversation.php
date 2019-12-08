<?php

namespace App\Models;

use App\Custom\CustomModel;

class EmployeeConversation extends CustomModel
{
    protected $fillable = [
        'employee_id',
        'message',
        'conversation_id'
    ];
    //
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
