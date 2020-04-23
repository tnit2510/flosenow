<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
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
            'label' => $this->label,
            'title' => $this->title,
            'slug' => route('announcements.show', $this->slug),
            'content' => $this->content,
            'thumbnail' => public_path('thumbnails' . $this->thumbnail),
        ];
    }
}
