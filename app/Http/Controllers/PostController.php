<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $posts = Cache::remember('posts', 60, function () {
            return Post::with('user', 'comments')->get();
        });

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        $cacheKey = "post.{$post->id}";

        $post = Cache::remember($cacheKey, 60, function () use ($post) {
            return $post;
        });

        return response()->json($post, 200);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $post->update($validated);

        Cache::forget("post.{$post->id}");

        return response()->json($post, 200);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        Cache::forget("comment.{$post->id}");

        return response()->json(null, 204);
    }
}
