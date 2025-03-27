<?php

namespace App\Domain\Entities;

class Country
{
    public string $code;
    public string $name;
    public ?string $wikipediaParagraph;
    public array $videos;

    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
        $this->videos = [];
    }
}
