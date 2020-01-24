<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'photos', 'duration'
    ];

    public function places(){
        return $this->belongsToMany(Place::class, 'place_tour', 'tour_id', 'place_id');
    }
}
