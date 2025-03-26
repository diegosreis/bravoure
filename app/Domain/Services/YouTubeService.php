<?php
namespace App\Domain\Services;

use App\Domain\Entities\Country;
use App\Domain\Entities\Video;
use App\Domain\ValueObjects\Thumbnail;
use Google\Client;
use Google\Service\YouTube;

class YouTubeService
{
    private YouTube $youtube;

    public function __construct()
    {
        $client = new Client();
        $client->setApplicationName('Bravoure Challenge');
        $client->setDeveloperKey(config('services.youtube.key'));
        $this->youtube = new YouTube($client);
    }

    /**
     * @param string $countryCode
     * @return array<Video>
     * @throws \Google\Exception
     */
    public function getMostPopularVideos(string $countryCode): array
    {
        $videos =[];
        $searchResult = $this->youtube->videos->listPopular('snippet', [
            'chart' => 'mostPopular',
            'regionCode' => $countryCode,
            'maxResults' => 10,
        ]);

        foreach ($searchResult->getItems() as $item) {
            $snippet = $item->getSnippet();
            $thumbnail = new Thumbnail(
                $snippet->getThumbnails()->getDefault()->getUrl(),
                $snippet->getThumbnails()->getHigh()->getUrl()
            );
            $videos= new Video(
                $item->getId(),
                $snippet->getTitle(),
                $snippet->getDescription(),
                $thumbnail
            );
        }

        return $videos;
    }
}
