<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'username',
        'telegram_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
