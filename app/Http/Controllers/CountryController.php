<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetEnrichedCountryDataUseCase;
use App\Domain\Entities\Country;
use App\Domain\Repositories\Cache\CountryCacheHandler;
use App\Domain\Repositories\CountryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    private GetEnrichedCountryDataUseCase $getEnrichedCountryDataUseCase;
    private CountryCacheHandler $cacheHandler;
    private CountryRepository $countryRepository;

    public function __construct(
        GetEnrichedCountryDataUseCase $getEnrichedCountryDataUseCase,
        CountryCacheHandler           $cacheHandler,
        CountryRepository             $countryRepository
    )
    {
        $this->getEnrichedCountryDataUseCase = $getEnrichedCountryDataUseCase;
        $this->cacheHandler = $cacheHandler;
        $this->countryRepository = $countryRepository;
    }

    public function getCountryData(Request $request, string $code): JsonResponse
    {
        try {

            // Validate the country code
            $this->validateCountryCode($code);

            $countryDataCacheKey = $this->cacheHandler->generateCountryDataCacheKey($code);

            // Attempt to retrieve all data for the country from cache
            $cachedCountryData = $this->cacheHandler->getCachedCountryData($countryDataCacheKey);
            if ($cachedCountryData) {
                $country = $this->countryRepository->rehydrateCountryEntity($cachedCountryData);
            } else {
                // Execute the use case to get the enriched country data
                $country = $this->getEnrichedCountryDataUseCase->execute($code);

                if (!$country) {
                    return response()->json(['message' => 'Country not found'], Response::HTTP_NOT_FOUND);
                }

                // Store all data in cache
                $this->cacheHandler->storeCachedCountryData($countryDataCacheKey, (array)$country);
            }

            // Transform the country data for the response
            $transformedData = $this->transformCountryResponse($country, $request->query('offset'), $request->query('page'));
            return response()->json($transformedData, Response::HTTP_OK);

        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Error fetching country data: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Transforms the country data into the desired response format.
     *
     * @param Country $country
     * @param int|null $offset
     * @param int|null $page
     * @return array
     */
    private function transformCountryResponse(Country $country, ?int $offset, ?int $page): array
    {
        $videos = $country->videos;

        $paginatedVideos = $this->paginateArray($videos, $offset, $page);

        $pagination = [
            'offset' => $offset ?? 0,
            'page' => $page ?? 1,
            'total_videos' => count($videos),
            'per_page' => 5,
            'total_pages' => ceil(count($videos) / 5),
        ];

        $country->pagination = $pagination;
        $country->videos = $paginatedVideos;

        return (array)$country;
    }

    /**
     * Paginates an array of videos.
     *
     * @param array $items
     * @param int|null $offset
     * @param int|null $page
     * @param int $perPage
     * @return array
     */
    private function paginateArray(array $items, ?int $offset = 0, ?int $page = 1, int $perPage = 5): array
    {
        $offset = $offset ?? 0;
        $page = $page ?? 1;
        $offset = ($page - 1) * $perPage + $offset;
        return array_slice($items, $offset, $perPage);
    }

    /**
     * Validates the country code.
     *
     * @param string $code
     * @throws \InvalidArgumentException
     */
    private function validateCountryCode(string $code): void
    {
        $validCountryCodes = config('countries.valid_codes');
        if (!in_array(strtolower($code), $validCountryCodes)) {
            throw new \InvalidArgumentException('Invalid country code.');
        }
    }
}
