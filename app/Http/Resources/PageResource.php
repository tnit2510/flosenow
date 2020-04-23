<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'name' => $this->name,
            'slug' => route('pages.show', $this->slug),
            'avatar' => public_path('avatars' . $this->avatar),
            'cover' => public_path('covers' . $this->cover),
            'posts_count' => $this->posts->count(),
        ];
    }
}
