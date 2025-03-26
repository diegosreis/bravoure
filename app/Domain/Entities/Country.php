<?php
namespace App\Domain\Entities;

class Country
{
    private string $code;
    private string $name;
    private ?string $wikipediaParagraph;
    private array $videos;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
        $this->videos = [];
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setWikipediaParagraph(?string $wikipediaParagraph): void
    {
        $this->wikipediaParagraph = $wikipediaParagraph;
    }

    public function getWikipediaParagraph(): ?string
    {
        return $this->wikipediaParagraph;
    }

    public function setVideos(array $videos): void
    {
        $this->videos = $videos;
    }

    public function getVideos(): array
    {
        return $this->videos;
    }
}
