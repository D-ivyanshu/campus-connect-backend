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
            'data'  => [
                'type' => 'users', 
                'user_id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'year' => $this->year,
                    'avatar' => $this->avatar, 
                    'banner' => $this->banner,
                    'course' => $this->course,
                    'about' => $this->about,
                    'branch' => $this->branch,
                    'social_links' => $this->social_links,
                    'notifications_configuration' => $this->notifications_configuration,
                    'email_verified_at' => $this->email_verified_at
                ],
                'token' => $this->token,
            ],
          
        ];
    }
}
