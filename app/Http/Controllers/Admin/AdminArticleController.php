<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    // Liste
    public function index(Request $request)
    {
        $query = Article::with(['category', 'user']);

        // Filtreleme
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Sıralama - order'a göre
        $articles = $query->orderBy('order', 'asc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);

        $categories = ArticleCategory::active()->ordered()->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    // Oluştur Formu
    public function create()
    {
        $categories = ArticleCategory::active()->ordered()->get();
        return view('admin.articles.create', compact('categories'));
    }

    // Kaydet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:article_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
        ]);

        // Slug oluştur
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Görsel yükle
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        // Kullanıcı ID
        $validated['user_id'] = Auth::id();

        // Order boşsa, son sıra ver
        if (empty($validated['order'])) {
            $validated['order'] = Article::max('order') + 1;
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Makale başarıyla oluşturuldu.');
    }

    // Düzenle Formu
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = ArticleCategory::active()->ordered()->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    // Güncelle
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:article_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'order' => 'nullable|integer|min:0',
        ]);

        // Slug oluştur
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Yeni görsel yüklendiyse
        if ($request->hasFile('featured_image')) {
            // Eski görseli sil
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Makale başarıyla güncellendi.');
    }

    // Sil
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // Görseli sil
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Makale başarıyla silindi.');
    }

    // Sıralama Güncelleme (AJAX)
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:articles,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Article::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
