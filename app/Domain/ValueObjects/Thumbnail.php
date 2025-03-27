<?php

namespace App\Domain\ValueObjects;

class Thumbnail
{
    public string $normal;
    public string $high;

    public function __construct(string $normal, string $high)
    {
        $this->normal = $normal;
        $this->high = $high;
    }
}
