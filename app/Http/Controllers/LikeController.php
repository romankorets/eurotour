<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Like;
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

    public function update(Request $request, $userId, $placeId)
    {
        $like = Like::all()->where('user_id', $userId)->where('place_id', $placeId)->first();
        $like->fill([
           'value' => $request->get('value')
        ]);
        $like->save();
    }

    public function destroy($userId, $placeId)
    {
        $like = Like::all()->where('user_id', $userId)->where('place_id', $placeId)->first();
        $like->delete();
    }
}
