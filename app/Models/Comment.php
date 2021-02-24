<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status_id',
        'body',
    ];

    /**
     * Get the user that owns the comment.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status that owns the comment.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the likes for the comment.
     * 
     * @return @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    /**
     * Like the comment
     *
     * @param \App\Models\User $user
     * @return $this
     */
    public function like($user)
    {
        $this->likes()->firstOrCreate([
            'user_id' => $user->id
        ]);

        return $this;
    }

    /**
     * Unlike the comment
     *
     * @param \App\Models\User $user
     * @return $this
     */
    public function unlike($user)
    {
        $this->likes()
            ->where('user_id', $user->id)
            ->delete();

        return $this;
    }

    /**
     * Check if the comment is liked by the user
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isLiked($user)
    {
        return $user 
            ? $this->likes()->where('user_id', $user->id)->exists()
            : false;
    }

    /**
     * Returns how many likes has the comment
     *
     * @return int
     */
    public function likesCount()
    {
        return $this->likes()->count();
    }
}
