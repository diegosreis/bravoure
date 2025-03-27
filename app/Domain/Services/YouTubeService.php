<?php

namespace App\Domain\Services;

use App\Domain\Entities\Video;
use App\Domain\ValueObjects\Thumbnail;
use Google\Client;
use Google\Exception;
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
     * @throws Exception
     */
    public function getMostPopularVideos(string $countryCode): array
    {
        $videos = [];

        if (strtoupper($countryCode) === 'UK') {
            $countryCode = 'GB';
        }

        $searchResult = $this->youtube->videos->listVideos('snippet', [
            'chart' => 'mostPopular',
            'regionCode' => strtoupper($countryCode),
            'maxResults' => 10,
        ]);

        $items = $searchResult->getItems();
        foreach ($items as $item) {
            $snippet = $item->getSnippet();
            $thumbnail = new Thumbnail(
                $snippet->getThumbnails()->getDefault()->getUrl(),
                $snippet->getThumbnails()->getHigh()->getUrl()
            );
            $videos[] = new Video(
                $item->getId(),
                $snippet->getTitle(),
                $snippet->getDescription(),
                $thumbnail
            );
        }
        return $videos;
    }
}
