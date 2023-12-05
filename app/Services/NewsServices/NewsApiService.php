<?php

namespace App\Services\NewsServices;

use App\DTO\NewsDataDTO;
use App\Services\Contracts\NewsServiceInterface;
use Exception;

/**
 * Class NewsApiService
 *
 * News service implementation for fetching news from the News API.
 */
class NewsApiService extends BaseNewsService implements NewsServiceInterface
{
    /**
     * API response mapping for transforming external API data to NewsDataDTO.
     *
     * @var array
     */
    protected array $mapping = [
        'title'             => 'title',
        'category'          => 'N/A',
        'author_first_name' => 'author',
        'author_last_name'  => 'N/A',
        'detail_link'       => 'url',
        'published_at'      => 'publishedAt',
    ];

    /**
     * The endpoint for the News API.
     *
     * @var string
     */
    protected string $endpoint = '/everything';

    /**
     * Get news from the News API.
     *
     * @return NewsDataDTO
     * @throws Exception
     */
    public function getNews(): NewsDataDTO
    {
        // Set specific API keys for the News API
        $this->setApiConfig('apiKeys', [
            'resultsKey' => 'articles',
        ]);

        // Build the request parameters
        $responseData = $this->fetchData($this->endpoint,  [
            'apiKey'  => config('news.services.' . $this->getSource() . '.apiKey'),
            'q'       => 'article',
            'from'    => config('news.dates.from'),
            'to'      => config('news.dates.to'),
            'sortBy'  => 'publishedAt',
        ]);

        return $this->createNewsDataDTO($responseData, $this->mapping);
    }
}
