<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Like;
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

    public function update(Request $request, $userId, $placeId)
    {
        $user = User::findOrFail($userId);
        $like = $user->likes()->where('place_id', $placeId)->first();
        $like->fill([
            'value' => $request->get('value')
        ]);
        $like->save();
    }

    public function destroy($userId, $placeId)
    {
        $user = User::find($userId);
        $like = $user->likes()->where('place_id', $placeId)->first();
        $like->delete();
    }
}
