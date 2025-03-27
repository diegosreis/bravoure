<?php
namespace App\Domain\Services;

use App\Domain\Entities\Country;

class DataEnrichmentService
{
    private YouTubeService $youTubeService;
    private WikipediaService $wikipediaService;

    public function __construct(YouTubeService $youTubeService, WikipediaService $wikipediaService)
    {
        $this->youTubeService = $youTubeService;
        $this->wikipediaService = $wikipediaService;
    }

    public function enrichCountry(Country $country): Country
    {
        $country->setWikipediaParagraph($this->wikipediaService->getInitialParagraph($country->getName()));
        $country->setVideos($this->youTubeService->getMostPopularVideos($country->getCode()));
        return $country;
    }
}
