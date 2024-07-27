<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::with('user', 'commentable')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'post_id' => 'nullable|exists:posts,id',
            'commentable_id' => 'required',
            'commentable_type' => 'required|string',
        ]);

        $comment = Comment::create($validated);

        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        return $comment->load('user', 'commentable');
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'post_id' => 'nullable|exists:posts,id',
            'commentable_id' => 'sometimes|required',
            'commentable_type' => 'sometimes|required|string',
        ]);

        $comment->update($validated);

        return response()->json($comment, 200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
    }
}
