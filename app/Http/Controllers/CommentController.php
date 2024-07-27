<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function index()
    {
        $cacheKey = 'comments.all'; // Kľúč pre cache
        $comments = Cache::remember($cacheKey, 60, function () {
            return Comment::with('user', 'commentable')->get();
        });

        return response()->json($comments, 200);
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

        Cache::forget('comments.all');

        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        $cacheKey = "comment.{$comment->id}";

        $comment = Cache::remember($cacheKey, 60, function () use ($comment) {
            return $comment->load('user', 'commentable');
        });

        return response()->json($comment, 200);
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

        Cache::forget("comment.{$comment->id}");

        return response()->json($comment, 200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        Cache::forget("comment.{$comment->id}");

        return response()->json(null, 204);
    }
}
