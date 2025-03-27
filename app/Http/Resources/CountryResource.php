<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'wikipedia_paragraph' => $this->getWikipediaParagraph(),
            'videos' => VideoResource::collection($this->getVideos()),
            'pagination' => $this->when(isset($this->pagination), $this->pagination),
        ];
    }

    public function getCode() {
        return $this->resource->getCode();
    }

    public function getName() {
        return $this->resource->getName();
    }

    public function getWikipediaParagraph() {
        return $this->resource->getWikipediaParagraph();
    }

    public function getVideos() {
        return $this->resource->getVideos();
    }
}
