<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'rating', 'photos', 'lat', 'lng'
    ];

    protected $table = 'places';

    public function comments(){
        return $this->hasMany(Comment::class, 'place_id');
    }

    public function tours(){
        return $this->belongsToMany(Tour::class, 'place_tour', 'place_id', 'tour_id')
            ->withPivot('isStartPlace', 'isFinishPlace');
    }
}
