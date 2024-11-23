<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::query();

        if ($request->has('keyword')) {
            $articles->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('content', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->has('category')) {
            $articles->where('category', $request->category);
        }

        if ($request->has('source')) {
            $articles->where('source', $request->source);
        }

        if ($request->has('date')) {
            $articles->whereDate('published_at', $request->date);
        }

        return $articles->paginate(10);
    }
    public function show($id)
    {
        // Find the article by ID
        $article = Article::find($id);

        // If article is not found, return a 404 error
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Return the article details in JSON format
        return response()->json($article);
    }

}
