<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create([
            'user_id' => session('user_id'),
            'place_id' => $request->get('place_id'),
            'body' => $request->get('body'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->fill([
            'enabled' => $request->get('enabled')
        ]);
        $comment->save();
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }
}
