<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentAdded;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Place $place)
    {
        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'place_id' => $place->id,
            'body' => $request->get('body'),
        ]);
        $comment->load('user');
        broadcast(new CommentAdded($comment))->toOthers();
        return $comment;
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
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
