<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\StatusResource;

class UserStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        $statuses = $user->statuses()
            ->with('user')
            ->with(['comments' => function ($query) {
                $query->with('user')->withCount('likes')->addIsLiked(auth()->id());
            }])
            ->withCount('likes')
            ->addIsLiked(auth()->id())
            ->latest()
            ->paginate();
        return StatusResource::collection($statuses);
    }

}
