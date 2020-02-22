<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Like;
use App\Place;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(StoreLikeRequest $request, Place $place)
    {
        $like = Like::create([
            'value' => $request->get('value'),
            'user_id' => Auth::user()->id,
            'place_id' => $place->id
        ]);
        $like->load('user');
        return $like;
    }

    public function update(Request $request, Place $place)
    {
        $user = Auth::user();
        $like = $user->likes()->where('place_id', $place->id)->first();
        $like->fill([
            'value' => $request->get('value')
        ]);
        $like->save();
    }

    public function destroy(Place $place)
    {
        $user = Auth::user();
        $like = $user->likes()->where('place_id', $place->id)->first();
        $like->delete();
    }
}
