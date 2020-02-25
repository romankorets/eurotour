<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Like;
use App\Place;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(StoreLikeRequest $request, Place $place)
    {
        $like = Like::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'place_id' => $place->id,
            ],
            [
                'value' => $request->get('value')
            ]
        );
        $like->load('user');
        return $like;
    }

    public function destroy(Place $place)
    {
        $like = Auth::user()->likes()->where('place_id', $place->id)->first();
        $like->delete();
    }
}
