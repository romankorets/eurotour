<?php

namespace App\Http\Controllers;

use App\Events\LikeDeleted;
use App\Events\LikeAdded;
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
        broadcast(new LikeAdded($like))->toOthers();
        return $like;
    }

    public function destroy(Place $place)
    {
        $like = Auth::user()->likes()->where('place_id', $place->id)->first();
        broadcast(new LikeDeleted($like))->toOthers();
        $like->delete();
    }
}
