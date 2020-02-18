<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Like;
use App\Place;
use App\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(StoreLikeRequest $request)
    {
        $like = Like::create([
            'value' => $request->get('value'),
            'user_id' => $request->get('user_id'),
            'place_id' => $request->get('place_id')
        ]);
    }

    public function update(Request $request, User $user, Place $place)
    {
        $like = $user->likes()->where('place_id', $place->id)->first();
        $like->fill([
            'value' => $request->get('value')
        ]);
        $like->save();
    }

    public function destroy(User $user, Place $place)
    {
        $like = $user->likes()->where('place_id', $place->id)->first();
        $like->delete();
    }
}
