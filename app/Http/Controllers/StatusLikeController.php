<?php

namespace App\Http\Controllers;

use App\Models\Status;

class StatusLikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Status  $status
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Status $status)
    {
        $status->like(auth()->user());
    }
}
