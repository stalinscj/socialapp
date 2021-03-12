<?php

namespace App\Http\Controllers;


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

        return view('friends.index', compact('friends'));
    }

}
