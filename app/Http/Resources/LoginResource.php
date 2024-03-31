<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public $token;
    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'email' => $this->resource->email,
                'name' => $this->resource->name,
                'id' => $this->resource->id,
                'avatar' => $this->resource->profile_pic,
                'notifications' => $this->notifications,
            ],
            'token' => $this->token,
        ];
    }
}
