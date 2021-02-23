<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'body'        => $this->body,
            'user_name'   => $this->user->name,
            'user_avatar' => 'img/default-avatar.jpg',
            'ago'         => $this->created_at->diffForHumans(),
            'is_liked'    => $this->isLiked(auth()->user()),
            'likes_count' => $this->likes_count,
        ];
    }
}
