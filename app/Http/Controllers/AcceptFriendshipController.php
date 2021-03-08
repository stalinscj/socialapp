<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class AcceptFriendshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $friendshipRequests = Friendship::where('recipient_id', auth()->id())
            ->with('sender')
            ->get();

        return view('friendships.index', compact('friendshipRequests'));
    }

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
                ['sender_id',    $sender->id],
                ['recipient_id', auth()->id()]
            ])
            ->update(['status' => Friendship::STATUS_ACCEPTED]);

        return response()->json(['friendship_status' => Friendship::STATUS_ACCEPTED]);
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
                ['sender_id',    $sender->id],
                ['recipient_id', auth()->id()]
            ])
            ->update(['status' => Friendship::STATUS_DENIED]);
        
        return response()->json(['friendship_status' => Friendship::STATUS_DENIED]);
    }
}
