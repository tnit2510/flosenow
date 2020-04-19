<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => route('posts.show', $this->slug),
            'description' => $this->description,
            'thumbnail' => config('app.url') . 'thumbnails/' . $this->thumbnail,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'hashtags' => HashtagResource::collection($this->hashtags),
        ];
    }
}
