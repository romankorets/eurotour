<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body',
    ];

    public function user(){
        return $this->hasOne(User::class, 'user_id');
    }

    public function place(){
        return$this->hasOne(Place::class, 'place_id');
    }
}
