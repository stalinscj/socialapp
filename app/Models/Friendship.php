<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;

    /**
     * Friendship status PENDING
     * 
     * @var string
     */
    const STATUS_PENDING  = 'PENDING';
    
    /**
     * Friendship status ACCEPTED
     * 
     * @var string
     */
    const STATUS_ACCEPTED = 'ACCEPTED';
    
    /**
     * Friendship status DENIED
     * 
     * @var string
     */
    const STATUS_DENIED   = 'DENIED';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'status',
    ];


    /**
     * Returns all availables status names 
     *
     * @return array
     */
    public static function statusNames()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_DENIED,
        ];
    }

    /**
     * Get the sender that owns the friendship.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipient that owns the friendship.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class);
    }
}
