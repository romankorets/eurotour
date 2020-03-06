<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'place_id',
        'body',
        'enabled',
    ];

    protected $table = 'comments';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function place()
    {
        return$this->belongsTo(Place::class, 'place_id');
    }
}
