<?php
namespace App\Domain\Repositories;

use App\Domain\Entities\Country;
use Illuminate\Support\Facades\Cache;

class CountryRepository
{
    public function getCountry(string $code): ?Country
    {
        $countryData = Cache::get("country_{$code}");

        if ($countryData) {
            return unserialize($countryData);
        }

        return null;
    }

    public function saveCountry(Country $country): void
    {
        Cache::put("country_{$country->getCode()}", serialize($country), 3600);
    }
}
