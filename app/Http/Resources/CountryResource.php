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
            'code' => $this->code,
            'name' => $this->name,
            'wikipedia_paragraph' => $this->wikipediaParagraph,
            'videos' => VideoResource::collection($this->videos),
            'pagination' => $this->when(isset($this->pagination), $this->pagination),
        ];
    }
}
