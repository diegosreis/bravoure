<?php

namespace App\Domain\Repositories\Cache;

use Illuminate\Support\Facades\Cache;

class CountryCacheHandler
{
    /**
     * Generates a unique cache key for a country's enriched data.
     *
     * @param string $countryCode
     * @return string
     */
    public function generateCountryDataCacheKey(string $countryCode): string
    {
        return sprintf('country_%s_data', $countryCode);
    }

    /**
     * Attempts to retrieve the enriched data for a country from the cache.
     *
     * @param string $cacheKey
     * @return mixed|null
     */
    public function getCachedCountryData(string $cacheKey): mixed
    {
        return Cache::get($cacheKey);
    }

    /**
     * Stores the enriched data for a country in the cache.
     *
     * @param string $cacheKey
     * @param array $data
     * @return void
     */
    public function storeCachedCountryData(string $cacheKey, array $data): void
    {
        Cache::put($cacheKey, $data, env('CACHE_TTL'));
    }
}
