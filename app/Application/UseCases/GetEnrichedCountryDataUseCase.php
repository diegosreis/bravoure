<?php
namespace App\Application\UseCases;

use App\Domain\Entities\Country;
use App\Domain\Services\DataEnrichmentService;
use App\Domain\Repositories\CountryRepository;

class GetEnrichedCountryDataUseCase
{
    private DataEnrichmentService $dataEnrichmentService;
    private CountryRepository $countryRepository;

    public function __construct(DataEnrichmentService $dataEnrichmentService, CountryRepository $countryRepository)
    {
        $this->dataEnrichmentService = $dataEnrichmentService;
        $this->countryRepository = $countryRepository;
    }

    /**
     * @param string $countryCode
     * @return Country|null
     */
    public function execute(string $countryCode): ?Country
    {
        $country = $this->countryRepository->getCountry($countryCode);

        if (!$country) {
            $country = $this->createCountry($countryCode);
        }

        $country = $this->dataEnrichmentService->enrichCountry($country);
        $this->countryRepository->saveCountry($country);

        return $country;
    }

    private function createCountry(string $countryCode): Country
    {
        $countries = [
            'uk' => 'United Kingdom',
            'nl' => 'Netherlands',
            'de' => 'Germany',
            'fr' => 'France',
            'es' => 'Spain',
            'it' => 'Italy',
            'gr' => 'Greece',
        ];

        if (!isset($countries[$countryCode])) {
            throw new \InvalidArgumentException("Invalid country code: {$countryCode}");
        }

        return new Country($countryCode, $countries[$countryCode]);
    }
}
