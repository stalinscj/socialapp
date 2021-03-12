<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $friendshipStatus = Friendship::betweenUsers(auth()->user(), $user)
            ->first()
            ->status ?? '';
        
        return view('users.show', compact('user', 'friendshipStatus'));
    }

}
