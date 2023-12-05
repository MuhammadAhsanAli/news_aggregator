<?php

namespace App\Services\NewsServices;

use App\DTO\NewsDataDTO;
use App\Services\Contracts\NewsServiceInterface;
use Exception;

/**
 * Class GuardianService
 *
 * News service implementation for fetching news from The Guardian API.
 */
class GuardianService extends BaseNewsService implements NewsServiceInterface
{
    /**
     * API response mapping for transforming external API data to NewsDataDTO.
     *
     * @var array
     */
    protected array $mapping = [
        'title'             => 'webTitle',
        'category'          => 'pillarName',
        'author_first_name' => 'tags.0.firstName',
        'author_last_name'  => 'tags.0.lastName',
        'detail_link'       => 'apiUrl',
        'published_at'      => 'webPublicationDate',
    ];

    /**
     * The endpoint for The Guardian API.
     *
     * @var string
     */
    protected string $endpoint = '/search';

    /**
     * Get news from The Guardian API.
     *
     * @return NewsDataDTO
     * @throws Exception
     */
    public function getNews(): NewsDataDTO
    {
        // Set specific API keys for The Guardian API
        $this->setApiConfig('apiKeys',  [
            'totalPages' => 'response.pages',
            'resultsKey' => 'response.results',
            'pageNumber' => 'page',
            'pagination' => true,
        ]);

        // Build the request parameters
        $responseData = $this->fetchData($this->endpoint, [
            'q'         => 'article',
            'show-tags' => 'contributor',
            'from-date' => config('news.dates.from'),
            'page-size' => config('news.services.' . $this->getSource() . '.pageSize'),
            'api-key'   => config('news.services.' . $this->getSource() . '.apiKey'),
        ]);

        return $this->createNewsDataDTO($responseData, $this->mapping);
    }
}
