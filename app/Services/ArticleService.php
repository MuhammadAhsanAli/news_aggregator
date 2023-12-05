<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Source;

class ArticleService
{
    /**
     * Get articles based on search queries and filters.
     *
     * @param array $filters
     * @return array
     */
    public function getArticles(array $filters): array
    {
        $query = Article::query()->with(['source:id,source']);

        $this->applySearchFilter($query, $filters['search']);
        $this->applyDateFilter($query, $filters['fromDate'], $filters['toDate']);
        $this->applyCategoryFilter($query, $filters['category']);
        $this->applySourceFilter($query, $filters['source']);
        $this->applyAuthorFilter($query, $filters['author']);

        $articles = $query->get();

        return ($articles->isEmpty()) ? ['message' => 'No data found.'] : $articles->toArray();
    }

    /**
     * Apply search filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $searchQuery
     * @return void
     */
    private function applySearchFilter($query, ?string $searchQuery): void
    {
        if (!empty($searchQuery)) {
            $query->where('title', 'LIKE', "%$searchQuery%");
        }
    }

    /**
     * Apply date filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return void
     */
    private function applyDateFilter($query, ?string $fromDate, ?string $toDate): void
    {
        if (!empty($fromDate)) {
            $query->whereDate('published_at', '>=', $fromDate);
        }

        if (!empty($toDate)) {
            $query->whereDate('published_at', '<=', $toDate);
        }
    }

    /**
     * Apply category filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $category
     * @return void
     */
    private function applyCategoryFilter($query, ?string $category): void
    {
        if (!empty($category)) {
            $query->where('category', '=', $category);
        }
    }

    /**
     * Apply source filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $sourceName
     * @return void
     */
    private function applySourceFilter($query, ?string $sourceName): void
    {
        if (!empty($sourceName)) {
            $sourceId = Source::where('source', $sourceName)->pluck('id');

            if ($sourceId) {
                $query->where('source_id', $sourceId);
            }
        }
    }

    /**
     * Apply author filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $author
     * @return void
     */
    private function applyAuthorFilter($query, ?string $author): void
    {
        if (!empty($author)) {
            $query->where(function ($query) use ($author) {
                $query->where('author_first_name', 'LIKE', "%$author%")
                    ->orWhere('author_last_name', 'LIKE', "%$author%");
            });
        }
    }
}
