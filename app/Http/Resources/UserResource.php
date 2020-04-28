<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email,
            'avatar' => config('app.url') . 'users/avatars/' . $this->avatar,
            'cover' => config('app.url') . 'users/covers/' . $this->cover,
            'bio' => $this->bio,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
