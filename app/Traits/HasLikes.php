<?php

namespace App\Traits;

use App\Models\Like;

trait HasLikes
{
    /**
     * Get the likes for the model.
     * 
     * @return @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    /**
     * Like the model
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
     * Unlike the model
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
     * Check if the model is liked by the user
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
     * Returns how many likes has the model
     *
     * @return int
     */
    public function likesCount()
    {
        return $this->likes()->count();
    }
}
