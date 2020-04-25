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
            'thumbnail' => public_path('thumbnails' . $this->thumbnail),
            'views' => $this->visits()->count(),
            'privacy' => $this->privacy == 0 
                ? 'Công Khai' 
                : (
                    $this->privacy == 1 
                        ? 'Chỉ Mình Tôi' 
                        : 'Người Theo Dõi'
                ),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'hashtags' => HashtagResource::collection($this->whenLoaded('hashtags')),
        ];
    }
}
