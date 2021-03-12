<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return view('friendships.index', [
            'friendshipRequests' => request()->user()->friendshipRequestsReceived()->with('sender')->get()
        ]);
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
        $friendship = $request->user()->acceptFriendRequestFrom($sender);

        return response()->json(['friendship_status' => $friendship->status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $sender
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $sender)
    {
        $friendship = request()->user()->denyFriendRequestFrom($sender);
        
        return response()->json(['friendship_status' => $friendship->status]);
    }
}
