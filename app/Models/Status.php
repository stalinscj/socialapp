<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'body',
    ];

    /**
     * Get the user that owns the status.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the status.
     * 
     * @return @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes for the status.
     * 
     * @return @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Like the post
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
     * Unlike the post
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
     * Check if the post is liked by the user
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
     * Returns how many likes has the status
     *
     * @return int
     */
    public function likesCount()
    {
        return $this->likes()->count();
    }
}
