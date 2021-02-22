<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Requests\StatusRequest;
use App\Http\Resources\StatusResource;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
      $statuses = Status::latest()->paginate();

      return StatusResource::collection($statuses);
    }

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

        return StatusResource::make($status);
    }
}
