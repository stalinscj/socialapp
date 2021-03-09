<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Comment;
use App\Events\CommentCreatedEvent;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;

class StatusCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, Status $status)
    {
        $comment = Comment::create([
            'user_id'   => auth()->id(),
            'status_id' => $status->id,
            'body'      => request('body') 
        ]);

        $commentResource = CommentResource::make($comment);

        CommentCreatedEvent::dispatch($commentResource);

        return $commentResource;
    }
}
