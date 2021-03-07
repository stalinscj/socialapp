<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class AcceptFriendshipController extends Controller
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
            ])
            ->update(['status' => Friendship::STATUS_ACCEPTED]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $sender
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $sender)
    {
        Friendship::query()
            ->where([
                'sender_id'    => $sender->id,
                'recipient_id' => auth()->id(),
            ])
            ->update(['status' => Friendship::STATUS_DENIED]);
    }
}
