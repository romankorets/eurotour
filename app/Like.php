<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'value', 'user_id', 'place_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}
