<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FollowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => [
                'type' => 'users', 
                'user_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'year' => $this->year,
                    'avatar' => $this->avatar, 
                    'is_followed' => $this->is_followed_by_user,
                    'followers_count' => $this->followers()->count(),
                    'following_count' => $this->following()->count(),
                ]
            ],
        ];
    }
}
