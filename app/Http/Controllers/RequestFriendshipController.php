<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class RequestFriendshipController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $sender
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $sender)
    {
        Friendship::query()
            ->where([
                'sender_id'    => $sender->id,
                'recipient_id' => auth()->id(),
                'accepted'     => false,
            ])
            ->update(['accepted' => true]);
    }
}
