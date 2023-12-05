<?php

namespace App\Console\Commands;

use App\Models\Source;
use Illuminate\Console\Command;
use App\Services\NewsServiceSelector;
use App\Services\NewsProcessor;
use App\Repositories\NewsRepository;
use App\Services\Contracts\NewsServiceInterface;
use App\DTO\NewsDataDTO;

class UpdateNewsData extends Command
{
    protected $signature = 'news:update';
    protected $description = 'Update news data from live sources';

    private NewsServiceSelector $newsServiceSelector;
    private NewsProcessor $newsProcessor;
    private NewsRepository $newsRepository;

    public function __construct(
        NewsServiceSelector $newsServiceSelector,
        NewsProcessor $newsProcessor,
        NewsRepository $newsRepository
    ) {
        parent::__construct();
        $this->newsServiceSelector = $newsServiceSelector;
        $this->newsProcessor = $newsProcessor;
        $this->newsRepository = $newsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve all news sources
        $sources = Source::all();

        if ($sources->isEmpty()) {
            $this->info('No news sources found.');
            return;
        }

        // Iterate through each news source
        foreach ($sources as $source) {
            $this->processSource($source);
        }

        $this->info('News data updated successfully.');
    }

    /**
     * Process news for a specific source.
     *
     * @param Source $source
     * @return void
     */
    private function processSource(Source $source)
    {
        // Get the news service for the source
        $newsService = $this->newsServiceSelector->getNewsService($source->source);

        if (!$newsService) {
            $this->error("News service for source '{$source->source}' not available.");
            return;
        }

        // Fetch news data for the source
        $newsDataDTO = $this->fetchNewsData($source, $newsService);

        if (!$newsDataDTO) {
            return;
        }

        // Update news data in the database
        $this->updateNews($source, $newsDataDTO);
    }

    /**
     * Fetch news data for a specific source.
     *
     * @param Source $source
     * @param NewsServiceInterface $newsService
     * @return NewsDataDTO|null
     */
    private function fetchNewsData(Source $source, NewsServiceInterface $newsService): ?NewsDataDTO
    {
        return $newsService->setSource($source->source)->getNews();
    }

    /**
     * Update news data in the database.
     *
     * @param Source $source
     * @param NewsDataDTO $newsDataDTO
     * @return void
     */
    private function updateNews(Source $source, NewsDataDTO $newsDataDTO)
    {
        // Process and store news data
        $processedNews = $this->newsProcessor->processArticleData(
            $newsDataDTO->responseData,
            $newsDataDTO->mapping,
            $source->id,
            $newsDataDTO->validationRule
        );

        $this->newsRepository->storeNews($processedNews);

        $this->info("News data updated for source '{$source->source}'.");
    }
}
