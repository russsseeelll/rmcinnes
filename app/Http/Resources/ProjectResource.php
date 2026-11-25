<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'date' => $this->date,
            'category' => $this->category,
            'tags' => $this->tagList(),
            'summary' => $this->summary,
            'description' => $this->description,
            'link' => $this->link,
            'image_url' => $this->image ? asset($this->image) : null,
            'url' => route('portfolio.single', $this->slug),
        ];
    }
}
