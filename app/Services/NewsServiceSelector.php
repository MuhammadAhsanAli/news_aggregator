<?php

namespace App\Services;

use App\Services\Contracts\NewsServiceInterface;

/**
 * Class NewsServiceSelector
 *
 * Service class for selecting a specific NewsService instance based on the provided source.
 */
class NewsServiceSelector
{
    /**
     * Get the specific NewsService instance based on the provided source.
     *
     * @param string $source The source identifier.
     *
     * @return NewsServiceInterface|null The NewsService instance or null if not found.
     */
    public function getNewsService(string $source): ?NewsServiceInterface
    {
        $className = "App\Services\NewsServices\\" . $this->formatSourceToClassName($source);

        if (class_exists($className)) {
            return app($className);
        }

        return null;
    }

    /**
     * Format the source string to a valid class name.
     *
     * @param string $source The source identifier.
     *
     * @return string The formatted class name.
     */
    private function formatSourceToClassName(string $source): string
    {
        return str_replace('_', '', ucwords($source, '_')) . 'Service';
    }
}
