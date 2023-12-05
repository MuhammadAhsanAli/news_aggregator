<?php

namespace App\Services\Contracts;

use App\DTO\NewsDataDTO;
use Exception;

/**
 * Interface NewsServiceInterface
 *
 * Represents the contract for classes that fetch news from external sources.
 */
interface NewsServiceInterface
{
    /**
     * Get news from the external source.
     *
     * @return NewsDataDTO
     * @throws Exception
     */
    public function getNews(): NewsDataDTO;
}
