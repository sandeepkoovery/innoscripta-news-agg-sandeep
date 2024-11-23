<?php

namespace App\Http\Controllers;

use App\Services\NewsAggregatorService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsAggregator;
    protected $newsService;


    public function __construct(NewsAggregatorService $newsAggregator, NewsAggregatorService $newsService)
    {
        $this->newsAggregator = $newsAggregator;
        $this->newsService = $newsService;
    }

    public function getAggregatedNews()
    {
        $news = $this->newsAggregator->aggregateNews();

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ]);
    }
    public function getNews(Request $request)
    {
        $query = $request->input('query', 'technology'); // Default to 'technology' if not provided
        $category = $request->input('category', 'science'); // Default to 'science' if not provided

        $news = $this->newsService->aggregateNews($query, $category);

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ]);
    }

}
