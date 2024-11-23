<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsAggregatorService
{
    public function fetchFromNewsAPI(string $query = 'default')
    {
        $url = 'https://newsapi.org/v2/everything';
        $response = Http::get($url, [
            'q' => $query,
            'apiKey' => env('NEWS_API_KEY'),
        ]);

        if ($response->successful()) {
            return $response->json()['articles'];
        }

        return [];
    }

    public function fetchFromGuardianAPI(string $category = null)
    {
        $url = 'https://content.guardianapis.com/search';
        $response = Http::get($url, [
            'q' => $category,
            'api-key' => env('GUARD_API_KEY'),
        ]);

        if ($response->successful()) {
            return $response->json()['response']['results'] ?? [];
        }

        return [];
    }

    public function fetchFromNYTAPI(string $category = null)
    {
        $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
        $response = Http::get($url, [
            'q' => $category,
            'api-key' => env('NYT_API_KEY'),
        ]);

        if ($response->successful()) {
            return $response->json()['response']['docs'] ?? [];
        }

        return [];
    }

    // public function aggregateNews(string $query = 'default', string $category = null )
    // {
    //     $newsAPIArticles = $this->fetchFromNewsAPI($query);
    //     $guardianAPI = $this->fetchFromGuardianAPI($category);

    //     return array_merge($newsAPIArticles,$guardianAPI);
    // }
    public function aggregateNewsBasedOnPreferences($preferredSources, $preferredCategories, $preferredAuthors)
    {
        // Fetch news from external APIs and filter by the given preferences
        // Example logic: Fetch articles from preferred sources, categories, and authors
        $news = $this->fetchNewsFromExternalApis();

        // Filter articles based on preferences
        return array_filter($news, function ($article) use ($preferredSources, $preferredCategories, $preferredAuthors) {
            return (
                (empty($preferredSources) || in_array($article['source']['name'], $preferredSources)) &&
                (empty($preferredCategories) || in_array($article['category'], $preferredCategories)) &&
                (empty($preferredAuthors) || in_array($article['author'], $preferredAuthors))
            );
        });
    }
}
