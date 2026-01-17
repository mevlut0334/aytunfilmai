<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Makale Listesi
    public function index(Request $request)
    {
        $query = Article::with('category')->published();

        // Kategori filtresi
        if ($request->filled('category')) {
            $category = ArticleCategory::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
            $currentCategory = $category;
        } else {
            $currentCategory = null;
        }

        // Sıralama - order değerine göre
        $articles = $query->ordered()->paginate(12);

        // Kategoriler
        $categories = ArticleCategory::active()
            ->ordered()
            ->withCount(['articles' => function($q) {
                $q->published();
            }])
            ->get();

        return view('articles.index', compact('articles', 'categories', 'currentCategory'));
    }

    // Makale Detay
    public function show($slug)
    {
        $article = Article::with(['category', 'user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Görüntülenme sayısını artır
        $article->incrementViews();

        // İlgili makaleler (aynı kategoriden, order'a göre sıralı)
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->published()
            ->ordered()
            ->limit(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
