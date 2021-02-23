<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Comment;
use App\Http\Resources\CommentResource;

class StatusCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function store(Status $status)
    {
        $comment = Comment::create([
            'user_id'   => auth()->id(),
            'status_id' => $status->id,
            'body'      => request('body') 
        ]);

        return CommentResource::make($comment);
    }
}
