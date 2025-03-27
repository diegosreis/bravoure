<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(), // Use getter
            'title' => $this->getTitle(), // Use getter
            'description' => $this->getDescription(), // Use getter
            'thumbnail' => [
                'normal' => $this->getThumbnail()->getNormal(), // Use getter
                'high' => $this->getThumbnail()->getHigh(),   // Use getter
            ],
        ];
    }

    public function getId() {
        return $this->resource->getId();
    }

    public function getTitle() {
        return $this->resource->getTitle();
    }

    public function getDescription() {
        return $this->resource->getDescription();
    }

    public function getThumbnail() {
        return $this->resource->getThumbnail();
    }
}
