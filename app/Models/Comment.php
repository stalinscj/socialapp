<?php

namespace App\Models;

use App\Traits\HasLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, HasLikes;

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
     * Returns the comment's link
     *
     * @return string
     */
    public function getPath(): string
    {
        return route('statuses.show', $this->status) . '#comment-' . $this->id;
    }

}
