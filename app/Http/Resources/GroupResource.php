<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'slug' => route('groups.show', $this->slug),
            'cover' => public_path('covers' . $this->cover),
            'privacy' => $this->privacy == 0 ? 'Nhóm Công Khai' : 'Nhóm Riêng Tư',
            'posts_count' => $this->posts->count(),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
