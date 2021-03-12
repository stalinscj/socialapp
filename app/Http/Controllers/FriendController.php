<?php

namespace App\Http\Controllers;

use App\Models\Friendship;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $friends = request()->user()->friends()->get();

        $friendshipStatus = Friendship::STATUS_ACCEPTED;

        return view('friends.index', compact('friends', 'friendshipStatus'));
    }

}
