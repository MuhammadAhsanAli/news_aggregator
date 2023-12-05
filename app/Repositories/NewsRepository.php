<?php

namespace App\Repositories;

use App\Models\Article;

/**
 * Class NewsRepository
 *
 * Repository class for storing news data in the local database.
 */
class NewsRepository
{
    /**
     * Store news data in the local database.
     *
     * @param array $data The processed data to be stored.
     *
     * @return void
     */
    public function storeNews(array $data): void
    {
        Article::insert($data);
    }
}
