<?php

namespace App\Http\Controllers;

use App\Models\Status;

class StatusLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Status $status)
    {
        $status->like(auth()->user());

        return response()->json(['likes_count' => $status->likesCount()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Status $status)
    {
        $status->unlike(auth()->user());

        return response()->json(['likes_count' => $status->likesCount()]);
    }
}
