<?php
namespace App\Domain\ValueObjects;

class Thumbnail
{
    private string $normal;
    private string $high;

    public function __construct(string $normal, string $high)
    {
        $this->normal = $normal;
        $this->high = $high;
    }

    public function getNormal(): string
    {
        return $this->normal;
    }

    public function getHigh(): string
    {
        return $this->high;
    }
}
