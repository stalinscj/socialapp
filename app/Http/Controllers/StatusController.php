<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Requests\StatusRequest;

class StatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StatusRequest  $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StatusRequest $request)
    {
        $status = Status::create([
            'user_id' => auth()->id(),
            'body'    => request('body')
        ]);

        return response()->json(['body' => $status->body]);
    }
}
