<?php

namespace App\Http\Controllers;

use App\Models\DocumentationArticle;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function index()
    {
        $articles = DocumentationArticle::where('is_published', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('docs.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = DocumentationArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $allArticles = DocumentationArticle::where('is_published', true)
            ->orderBy('order')
            ->get()
            ->groupBy('category');

        return view('docs.show', compact('article', 'allArticles'));
    }
}
