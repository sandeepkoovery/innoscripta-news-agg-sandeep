<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;
use App\Models\Article;
use Carbon\Carbon;

class FetchNewsData extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news articles from APIs';

    protected $newsAggregator;

    public function __construct(NewsAggregatorService $newsAggregator)
    {
        parent::__construct();
        $this->newsAggregator = $newsAggregator;
    }

    public function handle()
    {
        $newsAPIData = $this->newsAggregator->fetchFromNewsAPI();
        $guardianAPIData = $this->newsAggregator->fetchFromGuardianAPI();
        $nytAPIData = $this->newsAggregator->fetchFromNYTAPI();

        if (count($newsAPIData)> 0) {
            foreach ($newsAPIData as $newsArticle) {
                $publishedAt = Carbon::parse($newsArticle['publishedAt'])->format('Y-m-d H:i:s');
                Article::updateOrCreate(
                    ['url' => $newsArticle['url']],
                    [
                        'title' => $newsArticle['title'],
                        'content' => $newsArticle['content'],
                        'author' => $newsArticle['author'],
                        'api_source' => 'news-api',
                        'published_at' => $publishedAt,
                        'source' => $newsArticle['source']['name'],
                    ]
                );
            }
            $this->info('Articles fetched from NEWS API and stored successfully.');
        }

        if (count($guardianAPIData)> 0) {
            foreach ($guardianAPIData as $guardArticle) {
                $publishedAt = Carbon::parse($guardArticle['webPublicationDate'])->format('Y-m-d H:i:s');
                Article::updateOrCreate(
                    ['url' => $guardArticle['webUrl']],
                    [
                        'title' => $guardArticle['sectionName'],
                        'category' => $guardArticle['sectionId'],
                        'content' => $guardArticle['webTitle'],
                        'url' => $guardArticle['webUrl'],
                        'api_source' => 'guardian-api',
                        'published_at' => $publishedAt,
                    ]
                );
            }
            $this->info('Articles fetched from Guardian API and stored successfully..');
        }

        if (count($nytAPIData)> 0) {
            foreach ($nytAPIData as $nytArticle) {
                $publishedAt = Carbon::parse($nytArticle['pub_date'])->format('Y-m-d H:i:s');
                Article::updateOrCreate(
                    ['url' => $nytArticle['web_url']],
                    [
                        'title' => $nytArticle['section_name'],
                        'source' => $nytArticle['source'],
                        //'category' => $nytArticle['sectionId'],
                        'content' => $nytArticle['lead_paragraph'],
                        'url' => $nytArticle['web_url'],
                        'api_source' => 'nyt-api',
                        'published_at' => $publishedAt,
                    ]
                );
            }
            $this->info('Articles fetched from NYT API and stored successfully..');
        }
    }
}

