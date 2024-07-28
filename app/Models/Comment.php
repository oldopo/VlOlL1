<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Comment",
 *     type="object",
 *     title="Comment",
 *     required={"content", "user_id", "commentable_id", "commentable_type"},
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="commentable_id", type="integer"),
 *     @OA\Property(property="commentable_type", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'post_id', 'commentable_id', 'commentable_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function getCachedComments()
    {
        return Cache::remember('comments.all', now()->addMinutes(60), function () {
            return self::all();
        });
    }
}
