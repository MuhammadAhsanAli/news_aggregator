<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Services\ArticleService;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    private ArticleService $articleService;
    private ResponseService $responseService;

    public function __construct(ArticleService $articleService, ResponseService $responseService)
    {
        $this->articleService = $articleService;
        $this->responseService = $responseService;
    }

    /**
     * Get articles based on search queries and filters.
     *
     * @param ArticleRequest $request The request containing search queries and filters.
     * @return JsonResponse
     */
    public function index(ArticleRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $articles = $this->getFilteredArticles($validatedData);
            return $this->responseService->jsonResponse($articles);
        } catch (\Exception $e) {
            return $this->responseService->jsonResponse(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Get filtered articles based on request data.
     *
     * @param array $validatedData
     * @return array
     */
    private function getFilteredArticles(array $validatedData): array
    {
        $filters = [
            'search' => $validatedData['search'] ?? null,
            'fromDate' => $validatedData['fromDate'] ?? null,
            'toDate' => $validatedData['toDate'] ?? null,
            'author' => $validatedData['author'] ?? null,
            'category' => $validatedData['category'] ?? null,
            'source' => $validatedData['source'] ?? null,
        ];

        return $this->articleService->getArticles($filters);
    }
}
