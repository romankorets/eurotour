<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'rating', 'photos'
    ];

    public function comments(){
        return $this->hasMany(Comment::class, 'place_id');
    }
}
