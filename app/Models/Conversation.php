<?php

namespace App\Models;

use App\Custom\CustomModel;

class Conversation extends CustomModel
{
    //
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
