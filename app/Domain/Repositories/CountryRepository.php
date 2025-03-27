<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Country;
use App\Domain\Entities\Video;
use Illuminate\Support\Facades\Cache;
use App\Domain\ValueObjects\Thumbnail;

class CountryRepository
{
    public function getCountry(string $code): ?Country
    {
        $countryData = Cache::get("country_{$code}");

        if ($countryData) {
            return $this->rehydrateCountryEntity((array)unserialize($countryData));
        }

        return null;
    }

    public function saveCountry(Country $country): void
    {
        Cache::put("country_{$country->code}", serialize($country), env('CACHE_TTL'));
    }

    /**
     * Rehydrates the cached data into a Country entity.
     *
     * @param array $cachedData
     * @return Country
     */
    public function rehydrateCountryEntity(array $cachedData): Country
    {
        $country = new Country($cachedData['code'], $cachedData['name']);
        $country->wikipediaParagraph = $cachedData['wikipediaParagraph'];

        // Rehydrate videos
        $videos = [];
        foreach ($cachedData['videos'] as $videoData) {
            $thumbnail = new Thumbnail($videoData->thumbnail->normal, $videoData->thumbnail->high);
            $video = new Video(
                $videoData->id,
                $videoData->title,
                $videoData->description,
                $thumbnail
            );
            $videos[] = $video;
        }
        $country->videos = $videos;
        return $country;
    }
}
