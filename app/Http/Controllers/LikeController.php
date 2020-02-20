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
        $parsedUrl = parse_url(url()->previous());
        $parsedUrl['query'];
        parse_str($parsedUrl['query'], $querySlug);

        $place = Place::where('slug', $querySlug)->first();

        $user = User::findOrFail(session('user_id'));

        $like = Like::create([
            'value' => $request->get('value'),
            'user_id' => $user->id,
            'place_id' => $place->id
        ]);
    }

    public function update(Request $request)
    {
        $parsedUrl = parse_url(url()->previous());
        $parsedUrl['query'];
        parse_str($parsedUrl['query'], $querySlug);

        $place = Place::where('slug', $querySlug)->first();

        $user = User::findOrFail(session('user_id'));
        $like = $user->likes()->where('place_id', $place->id)->first();
        $like->fill([
            'value' => $request->get('value')
        ]);
        $like->save();
    }

    public function destroy()
    {
        $parsedUrl = parse_url(url()->previous());
        $parsedUrl['query'];
        parse_str($parsedUrl['query'], $querySlug);

        $place = Place::where('slug', $querySlug)->first();

        $user = User::findOrFail(session('user_id'));
        $like = $user->likes()->where('place_id', $place->id)->first();
        $like->delete();
    }
}
