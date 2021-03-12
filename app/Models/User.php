<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Returns the user profile link
     *
     * @return string
     */
    public function link()
    {
        return route('users.show', $this);
    }

    /**
     * Get the user's avatar.
     *
     * @return string
     */
    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    /**
     * Returns the user avatar link
     *
     * @return string
     */
    public function avatar()
    {
        return '/img/default-avatar.jpg';
    }

    /**
     * Get the statuses for the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * Get the friendship requests that the user has received
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function friendshipRequestsReceived()
    {
        return $this->hasMany(Friendship::class, 'recipient_id');
    }

    /**
     * Get the friendship requests that the user has sent
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function friendshipRequestsSent()
    {
        return $this->hasMany(Friendship::class, 'sender_id');
    }

    /**
     * Get the user'sf friends
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function friends()
    {
        $friends = User::query()
            ->whereIn('id', 
                $this->friendshipRequestsSent()
                    ->select('recipient_id')
                    ->where('status', Friendship::STATUS_ACCEPTED)
            )
            ->orWhereIn('id', 
                $this->friendshipRequestsReceived()
                    ->select('sender_id')
                    ->where('status', Friendship::STATUS_ACCEPTED)
            )
            
        ;

        return $friends;
    }

    /**
     * Create or get a friend request to recipient.
     *
     * @param \App\Models\User $recipient
     * @return \App\Models\Friendship
     */
    public function sendFriendRequestTo($recipient)
    {
        $friendship = $this->friendshipRequestsSent()
            ->firstOrCreate(['recipient_id' => $recipient->id])->fresh();

        return $friendship;
    }

    /**
     * Accept a friend request from sender.
     *
     * @param \App\Models\User $sender
     * @return \App\Models\Friendship
     */
    public function acceptFriendRequestFrom($sender)
    {
        $friendship = $this->friendshipRequestsReceived()
            ->where('sender_id', $sender->id)
            ->first();
            
        $friendship->update(['status' => Friendship::STATUS_ACCEPTED]);

        return $friendship;
    }

    /**
     * Deny a friend request from sender.
     *
     * @param \App\Models\User $sender
     * @return \App\Models\Friendship
     */
    public function denyFriendRequestFrom($sender)
    {
        $friendship = $this->friendshipRequestsReceived()
            ->where('sender_id', $sender->id)
            ->first();
            
        $friendship->update(['status' => Friendship::STATUS_DENIED]);

        return $friendship;
    }

}
