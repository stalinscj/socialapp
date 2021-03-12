<?php

namespace App\Traits;

use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Str;
use App\Events\ModelLikedEvent;
use App\Events\ModelUnlikedEvent;

trait HasLikes
{
    /**
     * Get the likes for the model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
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

        ModelLikedEvent::dispatch($this, $user);

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

        ModelUnlikedEvent::dispatch($this);

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

    /**
     * Scope a query to include is_liked attribute checking if the user likes the model.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Http\Modules\User\User|string|id $user
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAddIsLiked($query, $user)
    {
        $user = $user instanceof User ? $user->id : $user;
        
        $likeableTable = $this->getTable();
        $likeableModel = get_class($this);

        $subQuery = Like::selectRaw('CASE WHEN COUNT(1) THEN 1 ELSE 0 END')
            ->whereRaw("likeable_id = $likeableTable.id")
            ->where('likeable_type', $likeableModel)
            ->where('user_id', $user);

        $query->selectSub($subQuery, 'is_liked');

        return $query;
    }

    /**
     * Returns the event channel name for the model
     *
     * @return void
     */
    public function getEventChannelName()
    {
        $modelName = class_basename($this);

        $snake = Str::snake(Str::plural($modelName), '-');

        $eventChannelName = "$snake.{$this->getKey()}.likes";
        
        return $eventChannelName;
    }

    /**
     * Returns the model's link
     *
     * @return string
     */
    abstract public function getPath() : string;
    
}
