<?php
namespace App\Domain\Services;

use App\Domain\Entities\Country;
use Google\Exception;

class DataEnrichmentService
{
    private YouTubeService $youTubeService;
    private WikipediaService $wikipediaService;

    public function __construct(YouTubeService $youTubeService, WikipediaService $wikipediaService)
    {
        $this->youTubeService = $youTubeService;
        $this->wikipediaService = $wikipediaService;
    }

    /**
     * @throws Exception
     */
    public function enrichCountry(Country $country): Country
    {
        $country->wikipediaParagraph = $this->wikipediaService->getInitialParagraph($country->name);
        $country->videos = $this->youTubeService->getMostPopularVideos($country->code);
        return $country;
    }
}
