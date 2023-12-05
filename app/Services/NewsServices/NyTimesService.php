<?php

namespace App\Services\NewsServices;

use App\DTO\NewsDataDTO;
use App\Services\Contracts\NewsServiceInterface;
use Exception;

/**
 * Class NyTimesService
 *
 * News service implementation for fetching news from The New York Times API.
 */
class NyTimesService extends BaseNewsService implements NewsServiceInterface
{
    /**
     * API response mapping for transforming external API data to NewsDataDTO.
     *
     * @var array
     */
    protected array $mapping = [
        'title'             => 'lead_paragraph',
        'category'          => 'type_of_material',
        'author_first_name' => 'byline.person.firstname',
        'author_last_name'  => 'byline.person.lastname',
        'detail_link'       => 'web_url',
        'published_at'      => 'pub_date',
    ];

    /**
     * The endpoint for The New York Times API.
     *
     * @var string
     */
    protected string $endpoint = '/svc/search/v2/articlesearch.json';

    /**
     * Get news from The New York Times API.
     *
     * @return NewsDataDTO
     * @throws Exception
     */
    public function getNews(): NewsDataDTO
    {
        // Set specific response API keys for The New York Times API
        $this->setApiConfig('apiKeys', [
            'resultsKey' => 'response.docs',
        ]);

        // Fetch data from the API
        $responseData = $this->fetchData($this->endpoint, [
            'api-key'    => config('news.services.' . $this->getSource() . '.apiKey'),
            'begin_date' => config('news.dates.from'),
            'end_date'   => config('news.dates.to'),
        ]);

        return $this->createNewsDataDTO($responseData, $this->mapping);
    }
}
