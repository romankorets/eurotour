<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $parsedUrl = parse_url(url()->previous());
        $parsedUrl['query'];
        parse_str($parsedUrl['query'], $querySlug);
        $place = Place::where('slug', $querySlug)->first();
        $comment = Comment::create([
            'user_id' => session('user_id'),
            'place_id' => $place->id,
            'body' => $request->get('body'),
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->fill([
            'enabled' => $request->get('enabled')
        ]);
        $comment->save();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
    }
}
