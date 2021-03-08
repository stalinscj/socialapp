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
        $friendship = Friendship::firstOrCreate([
            'sender_id'    => auth()->id(),
            'recipient_id' => $recipient->id,
        ])->fresh();

        return response()->json(['friendship_status' => $friendship->status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $frienship = Friendship::query()
            ->where([
                'sender_id'    => auth()->id(),
                'recipient_id' => $user->id,
            ])
            ->orWhere([
                ['sender_id', $user->id],
                ['recipient_id', auth()->id()]
            ])
            ->first();

        if ($frienship->status == Friendship::STATUS_DENIED) {
            return response()->json(['friendship_status' => Friendship::STATUS_DENIED]);    
        }

        return response()->json(['friendship_status' => $frienship->delete() ? 'DELETED' : '']);
    }
}
