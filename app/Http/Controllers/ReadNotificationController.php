<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class ReadNotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return $notification;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DatabaseNotification $notification)
    {
        $notification->markAsUnread();

        return $notification;
    }
}
