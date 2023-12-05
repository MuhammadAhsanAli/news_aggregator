<?php

namespace App\Services;

use App\Helper\ApiResponseHandler;
use App\Services\Contracts\NewsProcessorInterface;
use Carbon\Carbon;

/**
 * Class NewsProcessor
 *
 * Service class for processing news articles.
 */
class NewsProcessor implements NewsProcessorInterface
{
    private ApiResponseHandler $apiResponseHandler;
    private NewsValidator $newsValidator;

    /**
     * NewsProcessor constructor.
     *
     * @param ApiResponseHandler $apiResponseHandler
     * @param NewsValidator      $newsValidator
     */
    public function __construct(ApiResponseHandler $apiResponseHandler, NewsValidator $newsValidator)
    {
        $this->newsValidator = $newsValidator;
        $this->apiResponseHandler = $apiResponseHandler;
    }

    /**
     * Process article data based on the provided mapping.
     *
     * @param array $data
     * @param array $mapping
     * @param int   $sourceId
     * @param array $validationRule
     *
     * @return array
     */
    public function processArticleData(array $data, array $mapping, int $sourceId, array $validationRule): array
    {
        $processedData = [];

        foreach ($data as $article) {
            $processedArticle = $this->processSingleArticle($article, $mapping);

            if ($this->newsValidator->validate($processedArticle, $validationRule)) {
                $processedData[] = array_merge($processedArticle, [
                    'source_id' => $sourceId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        return $processedData;
    }

    /**
     * Process a single article based on the provided mapping.
     *
     * @param array $article
     * @param array $mapping
     *
     * @return array
     */
    private function processSingleArticle(array $article, array $mapping): array
    {
        $processedArticle = [];

        foreach ($mapping as $outputKey => $sourceKey) {
            $articleValue = $this->getArticleValue($article, $sourceKey);

            if ($outputKey === 'published_at') {
                $processedArticle[$outputKey] = Carbon::parse($articleValue)->toDateTimeString() ??  null;
            } else {
                $processedArticle[$outputKey] = $articleValue;
            }
        }

        return $processedArticle;
    }

    /**
     * Get the value from the article data based on the source key.
     *
     * @param array  $article
     * @param string $sourceKey
     *
     * @return mixed
     */
    private function getArticleValue(array $article, string $sourceKey): mixed
    {
        return $article[$sourceKey] ?? $this->apiResponseHandler->extractResults($article, $sourceKey) ?: "N/A";
    }
}
