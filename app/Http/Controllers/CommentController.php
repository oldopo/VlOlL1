<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="API Endpoints for Comments"
 * )
 */
class CommentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/comments",
     *     summary="Get all comments",
     *     tags={"Comments"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Comment"))
     *     )
     * )
     */
    public function index()
    {
        $cacheKey = 'comments.all'; // Kľúč pre cache
        $comments = Cache::remember($cacheKey, 60, function () {
            return Comment::with('user', 'commentable')->get();
        });

        return response()->json($comments, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Create a new comment",
     *     tags={"Comments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content", "user_id", "commentable_id", "commentable_type"},
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="commentable_id", type="integer"),
     *             @OA\Property(property="commentable_type", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/comments/{comment}",
     *     summary="Get a single comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     )
     * )
     */
    public function show(Comment $comment)
    {
        $cacheKey = "comment.{$comment->id}";

        $comment = Cache::remember($cacheKey, 60, function () use ($comment) {
            return $comment->load('user', 'commentable');
        });

        return response()->json($comment, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{comment}",
     *     summary="Update a comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="commentable_id", type="integer"),
     *             @OA\Property(property="commentable_type", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/comments/{comment}",
     *     summary="Delete a comment",
     *     tags={"Comments"},
     *     @OA\Parameter(
     *         name="comment",
     *         in="path",
     *         description="ID of the comment",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment deleted",
     *     )
     * )
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        Cache::forget("comment.{$comment->id}");

        return response()->json(null, 204);
    }
}
