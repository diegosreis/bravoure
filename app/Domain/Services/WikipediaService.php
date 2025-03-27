<?php

namespace App\Domain\Services;

use GuzzleHttp\Client;

class WikipediaService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://en.wikipedia.org/w/api.php',
        ]);
    }

    public function getInitialParagraph(string $countryName): ?string
    {
        try {
            $response = $this->client->get('', [
                'query' => [
                    'action' => 'query',
                    'format' => 'json',
                    'prop' => 'extracts',
                    'exintro' => true,
                    'explaintext' => true,
                    'titles' => $countryName,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $pages = $data['query']['pages'];
            $firstPage = reset($pages);

            return $firstPage['extract'] ?? null;
        } catch (\Exception $e) {
            //TODO: handle exception
            return null;
        }
    }
}
