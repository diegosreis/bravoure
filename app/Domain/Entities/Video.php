<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Thumbnail;

class Video
{
    public string $id;
    public string $title;
    public string $description;
    public Thumbnail $thumbnail;

    public function __construct(string $id, string $title, string $description, Thumbnail $thumbnail)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->thumbnail = $thumbnail;
    }
}
