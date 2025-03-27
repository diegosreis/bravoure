<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GetEnrichedCountryDataUseCase;
use App\Domain\Repositories\Cache\CountryCacheHandler;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    private GetEnrichedCountryDataUseCase $getEnrichedCountryDataUseCase;
    private CountryCacheHandler $cacheHandler;

    public function __construct(
        GetEnrichedCountryDataUseCase $getEnrichedCountryDataUseCase,
        CountryCacheHandler $cacheHandler
    ) {
        $this->getEnrichedCountryDataUseCase = $getEnrichedCountryDataUseCase;
        $this->cacheHandler = $cacheHandler;
    }

    public function getCountryData(Request $request, string $code): JsonResponse
    {
        try {
            // Validate the country code
            $this->validateCountryCode($code);

            // Build cache key based on request parameters
            $cacheKey = $this->cacheHandler->generateCacheKey($code, $request->query('offset'), $request->query('page'));

            // Attempt to retrieve data from cache
            $cachedData = $this->cacheHandler->getCachedData($cacheKey);
            $cachedData = null;

            if ($cachedData) {
                return response()->json($cachedData, Response::HTTP_OK);
            }

            // Execute the use case to get the enriched country data
            $country = $this->getEnrichedCountryDataUseCase->execute($code);

            if (!$country) {
                return response()->json(['message' => 'Country not found'], Response::HTTP_NOT_FOUND);
            }

            // Transform the country data for the response
            $transformedData = $this->transformCountryResponse($country, $request->query('offset'), $request->query('page'));

            // Store the transformed data in cache
            $this->cacheHandler->storeCachedData($cacheKey, $transformedData);

            return response()->json($transformedData, Response::HTTP_OK);

        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            \Log::error('Error fetching country data: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Transforms the country data into the desired response format.
     *
     * @param \App\Domain\Entities\Country $country
     * @param int|null $offset
     * @param int|null $page
     * @return array
     */
    private function transformCountryResponse(\App\Domain\Entities\Country $country, ?int $offset, ?int $page): array
    {
        $videos = $country->getVideos();
        $paginatedVideos = $this->paginateArray($videos, $offset, $page);

        $pagination = [
            'offset' => $offset ?? 0,
            'page' => $page ?? 1,
            'total_videos' => count($videos),
            'per_page' => 10,
            'total_pages' => ceil(count($videos) / 10),
        ];

        $country->pagination = $pagination;

        return (new CountryResource($country))->toArray(request());
    }

    /**
     * Paginates an array of videos.
     *
     * @param array $items
     * @param int|null $offset
     * @param int $page
     * @param int $perPage
     * @return array
     */
    private function paginateArray(array $items, ?int $offset = 0, ?int $page = 1, int $perPage = 10): array
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
        $validCountryCodes = ['uk', 'nl', 'de', 'fr', 'es', 'it', 'gr'];
        if (!in_array(strtolower($code), $validCountryCodes)) {
            throw new \InvalidArgumentException('Invalid country code.');
        }
    }
}
