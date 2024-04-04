<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {        
        $data = is_array($this->data) ? $this->data : json_decode($this->data, true);
        return [
            "data" =>[
                'type' => $data['type'],
                'id' => $this->id,
                'user' => [
                    'id' => $data['user']['id'],
                    'name' => $data['user']['name'],
                    'email' => $data['user']['email'],
                    'avatar' => $data['user']['avatar']
                ],
                'is_following' => $data['is_following'],
                'title' => $data['title'],
                'created_at' => $this->created_at->diffForHumans(),
                'read_at' => $this->read_at,
            ]
        ];
    }
}
