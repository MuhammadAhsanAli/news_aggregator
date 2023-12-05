<?php

namespace App\Helper;

/**
 * Class ApiResponseHandler
 *
 * Helper class for extracting results from an API response.
 */
class ApiResponseHandler
{
    /**
     * Extract results from the API response based on the provided results key.
     *
     * @param array  $response   The API response.
     * @param string $resultsKey The key to extract results from the response.
     *
     * @return array|int|string The extracted results or an empty array if the key is not found.
     */
    public function extractResults(array $response, string $resultsKey): array|int|string
    {
        $keys = explode('.', $resultsKey);

        foreach ($keys as $key) {
            if (isset($response[$key])) {
                $response = $response[$key];
            } else {
                return [];
            }
        }

        return $response;
    }
}
