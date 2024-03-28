<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\MediaCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'type' => 'posts', 
                'post_id' => $this->id,
                'attributes' => [
                    'posted_by' => new UserResource($this->user),
                    'comments' => new CommentCollection($this->comments),
                    'body' => $this->body,
                    'posted_at' => $this->created_at->diffForHumans(),
                    'media' => new MediaCollection($this-> media),
                ]
            ],
        ];
    }
}

