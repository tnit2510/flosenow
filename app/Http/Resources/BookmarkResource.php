<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
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
            'name' => $this->name,
            'slug' => route('bookmarks.show', $this->slug),
            'posts_count' => $this->posts->count(),
            // 'posts' => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
