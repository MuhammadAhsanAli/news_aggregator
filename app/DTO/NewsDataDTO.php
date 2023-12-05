<?php

namespace App\DTO;

/**
 * Class NewsDataDTO
 *
 * Data Transfer Object for news data.
 */
class NewsDataDTO
{
    /**
     * @var array $responseData
     */
    public array $responseData;

    /**
     * @var array $mapping
     */
    public array $mapping;

    /**
     * @var array $validationRule
     */
    public array $validationRule;

    /**
     * NewsDataDTO constructor.
     *
     * @param array $responseData   The response data from the news source.
     * @param array $mapping       The mapping rules for transforming data.
     * @param array $validationRule The validation rules for the data.
     */
    public function __construct(array $responseData, array $mapping, array $validationRule)
    {
        $this->responseData = $responseData;
        $this->mapping = $mapping;
        $this->validationRule = $validationRule;
    }
}

