<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $status = Status::create([
            'user_id' => auth()->id(),
            'body'    => request('body')
        ]);

        return response()->json(['body' => $status->body]);
    }
}
