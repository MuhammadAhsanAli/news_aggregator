<?php

namespace App\Services\Contracts;

/**
 * Interface NewsProcessorInterface
 *
 * Represents the contract for classes that process article data based on a mapping.
 */
interface NewsProcessorInterface
{
    /**
     * Process article data based on the provided mapping.
     *
     * @param array $data
     * @param array $mapping
     * @param int $sourceId
     * @param array $validationRule
     * @return array
     */
    public function processArticleData(array $data, array $mapping, int $sourceId, array $validationRule): array;
}
