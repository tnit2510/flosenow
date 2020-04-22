<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnouncementResource extends JsonResource
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
            'slug' => route('anouncements.show', $this->slug),
            'content' => $this->content,
            'thumbnail' => public_path('thumbnails/' . $this->thumbnail),
        ];
    }
}
