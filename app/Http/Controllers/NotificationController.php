<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
      $notifications = request()->user()->notifications()->limit(10)->get();
        
      return $notifications;
    }
}