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
     * @return @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }


    /**
     * Create or get a friend request to recipient.
     *
     * @param \App\Models\User $recipient
     * @return \App\Models\Friendship
     */
    public function sendFriendRequestTo($recipient)
    {
        $friendship = Friendship::firstOrCreate([
            'sender_id'    => $this->id,
            'recipient_id' => $recipient->id,
        ])->fresh();

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
        $friendship = Friendship::query()
            ->where([
                ['sender_id',    $sender->id],
                ['recipient_id', $this->id]
            ])
            ->first();
            
        $friendship->update(['status' => Friendship::STATUS_ACCEPTED]);

        return $friendship;
    }

}
