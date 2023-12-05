<?php

namespace App\Services\NewsServices;

use App\DTO\NewsDataDTO;
use App\Helper\ApiResponseHandler;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * BaseNewsService abstract class provides common functionalities for NewsService implementations.
 */
abstract class BaseNewsService
{
    protected Client $httpClient;
    protected ApiResponseHandler $apiResponseHandler;
    protected array $apiConfig = [];

    /**
     * Constructor for BaseNewsService.
     *
     * @param Client $httpClient
     * @param ApiResponseHandler $apiResponseHandler
     */
    public function __construct(Client $httpClient, ApiResponseHandler $apiResponseHandler)
    {
        $this->httpClient = $httpClient;
        $this->apiResponseHandler = $apiResponseHandler;
    }

    /**
     * Make a request to the external API.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws Exception
     */
    protected function makeRequest(string $endpoint, array $params): array
    {
        // Build the URL using the configured base URL and endpoint
        $url = config('news.services.' . $this->getSource() . '.baseUrl') . $endpoint;

        try {
            // Perform the HTTP request using Guzzle
            $response = $this->httpClient->get($url, ['query' => $params]);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                return json_decode($response->getBody(), true);
            }

            throw new Exception("Failed to fetch news from The {$this->getSource()} API. Status: {$statusCode}, Error: {$response->getReasonPhrase()}");
        } catch (RequestException $e) {
            throw new Exception("HTTP request failed: " . $e->getMessage());
        }
    }

    /**
     * Fetch data from the external API, handling pagination if configured.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws Exception
     */
    protected function fetchData(string $endpoint, array $params): array
    {
        if (!$this->hasPagination()) {
            return $this->fetchNonPaginatedData($endpoint, $params);
        }

        // Fetch paginated data
        $allData = [];
        $currentPage = 1;

        do {
            $params[$this->apiConfig['apiKeys']['pageNumber']] = $currentPage;
            $responseData = $this->makeRequest($endpoint, $params);

            $results = $this->apiResponseHandler->extractResults($responseData, $this->apiConfig['apiKeys']['resultsKey']);

            if (!empty($results)) {
                $allData = array_merge($allData, $results);
            }

            if (!empty($results) && $this->hasMorePages($this->apiResponseHandler->extractResults($responseData, $this->apiConfig['apiKeys']['totalPages']), $currentPage)) {
                $currentPage++;
            } else {
                break;
            }
        } while (true);

        return $allData;
    }

    /**
     * Check if there are more pages to fetch.
     *
     * @param int $totalPages
     * @param int $currentPage
     * @return bool
     */
    protected function hasMorePages(int $totalPages, int $currentPage): bool
    {
        return $currentPage < $totalPages;
    }

    /**
     * Retrieve news data from the external source.
     *
     * @return NewsDataDTO An object representing the retrieved news data.
     */
    abstract public function getNews(): NewsDataDTO;

    /**
     * Fetch data when there is only one API key and no pagination.
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws Exception
     */
    private function fetchNonPaginatedData(string $endpoint, array $params): array
    {
        // Fetch non-paginated data
        $responseData = $this->makeRequest($endpoint, $params);
        return $this->apiResponseHandler->extractResults($responseData, $this->apiConfig['apiKeys']['resultsKey']);
    }

    /**
     * Check if pagination is enabled.
     *
     * @return bool
     */
    private function hasPagination(): bool
    {
        return isset($this->apiConfig['apiKeys']['pagination']);
    }

    /**
     * Set the news source in the configuration.
     *
     * @param string $source The identifier for the external news source.
     * @return BaseNewsService
     */
    public function setSource(string $source): BaseNewsService
    {
        $this->setApiConfig('source', $source);
        return $this;
    }

    /**
     * Get the current news source from the configuration.
     *
     * @return string The identifier for the current external news source.
     */
    protected function getSource(): string
    {
        return $this->apiConfig['source'];
    }

    /**
     * Set a value in the apiConfig array.
     *
     * @param string $key
     * @param array|string $val
     * @return void
     */
    protected function setApiConfig(string $key, array|string $val): void
    {
        $this->apiConfig[$key] = $val;
    }

    /**
     * Create a NewsDataDTO instance from response data.
     *
     * @param array $responseData
     * @param array $mapping
     * @return NewsDataDTO
     */
    protected function createNewsDataDTO(array $responseData, array $mapping): NewsDataDTO
    {
        return new NewsDataDTO($responseData, $mapping, $this->getColumnValidationRules());
    }

    /**
     * Get validation rules for each column.
     *
     * @return array
     */
    protected function getColumnValidationRules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:500'],
            'category'         => ['required', 'string', 'max:255'],
            'author_first_name'=> ['nullable', 'string', 'max:255'],
            'author_last_name' => ['nullable', 'string', 'max:255'],
            'detail_link'      => ['required', 'url'],
            'published_at'     => ['required', 'date'],
        ];
    }
}
