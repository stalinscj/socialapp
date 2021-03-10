<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Events\StatusCreatedEvent;
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
      $statuses = Status::with('user')
            ->with(['comments' => function ($query) {
                $query->with('user')->withCount('likes')->addIsLiked(auth()->id());
            }])
            ->withCount('likes')
            ->addIsLiked(auth()->id())
            ->latest()
            ->paginate();
        
      return StatusResource::collection($statuses);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        return view('statuses.show', ['status' => StatusResource::make($status)]);
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
        $status = $request->user()->statuses()->create($request->validated());

        $statusResource = StatusResource::make($status);
    
        StatusCreatedEvent::dispatch($statusResource);

        return $statusResource;
    }
}
