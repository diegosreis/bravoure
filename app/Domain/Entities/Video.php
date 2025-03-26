<?php
namespace App\Domain\Entities;

use App\Domain\ValueObjects\Thumbnail;

class Video
{
    private string $id;
    private string $title;
    private string $description;
    private Thumbnail $thumbnail;

    public function __construct(string $id, string $title, string $description, Thumbnail $thumbnail)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->thumbnail = $thumbnail;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): Thumbnail
    {
        return $this->thumbnail;
    }
}
