<?php

namespace App\Domain\Repositories\Cache;

use Illuminate\Support\Facades\Cache;

class CountryCacheHandler
{
    /**
     * Generates a unique cache key based on the country code, offset, and page.
     *
     * @param string $countryCode
     * @param int|null $offset
     * @param int|null $page
     * @return string
     */
    public function generateCacheKey(string $countryCode, ?int $offset, ?int $page): string
    {
        return sprintf('country_%s_offset_%s_page_%s', $countryCode, $offset ?? 0, $page ?? 1);
    }

    /**
     * Attempts to retrieve country data from the cache.
     *
     * @param string $cacheKey
     * @return mixed|null
     */
    public function getCachedData(string $cacheKey): mixed
    {
        return Cache::get($cacheKey);
    }

    /**
     * Stores the transformed country data in the cache.
     *
     * @param string $cacheKey
     * @param array $data
     * @param int $ttl
     * @return void
     */
    public function storeCachedData(string $cacheKey, array $data, int $ttl = 3600): void
    {
        Cache::put($cacheKey, $data, $ttl);
    }
}
