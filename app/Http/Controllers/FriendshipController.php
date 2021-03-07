<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $recipient
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $recipient)
    {
        Friendship::firstOrCreate([
            'sender_id'    => auth()->id(),
            'recipient_id' => $recipient->id,
        ]);

        return response()->json(['friendship_status' => Friendship::STATUS_PENDING]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $recipient
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $recipient)
    {
        Friendship::query()
            ->where([
                'sender_id'    => auth()->id(),
                'recipient_id' => $recipient->id,
            ])
            ->delete();
        
        return response()->json(['friendship_status' => '']);
    }
}
