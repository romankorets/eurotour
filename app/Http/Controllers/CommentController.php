<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = Comment::create([
            'user_id' => $request->get('user_id'),
            'place_id' => $request->get('place_id'),
            'body' => $request->get('body'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFair($id);
        $comment->fill($request->all());
        $comment->save();
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }

    public function getCommentUser($id)
    {
        $comment = Comment::findOrFail($id);
        $user = $comment->user;
        return response()->json(json_encode($user));
    }
}
