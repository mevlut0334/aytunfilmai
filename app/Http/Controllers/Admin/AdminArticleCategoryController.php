<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleCategoryController extends Controller
{
    // Liste
    public function index()
    {
        $categories = ArticleCategory::withCount('articles')
            ->ordered()
            ->get();

        return view('admin.article-categories.index', compact('categories'));
    }

    // Oluştur Formu
    public function create()
    {
        return view('admin.article-categories.create');
    }

    // Kaydet
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:article_categories,slug',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // Slug oluştur
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Order boşsa, son sıra ver
        if (!isset($validated['order'])) {
            $validated['order'] = ArticleCategory::max('order') + 1;
        }

        // is_active - checkbox'tan geliyorsa 1, gelmiyorsa 0
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        ArticleCategory::create($validated);

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    // Düzenle Formu
    public function edit($id)
    {
        $category = ArticleCategory::findOrFail($id);
        return view('admin.article-categories.edit', compact('category'));
    }

    // Güncelle
    public function update(Request $request, $id)
    {
        $category = ArticleCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:article_categories,slug,' . $id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // Slug oluştur
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // is_active - checkbox'tan geliyorsa 1, gelmiyorsa 0
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $category->update($validated);

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    // Sil
    public function destroy($id)
    {
        $category = ArticleCategory::findOrFail($id);

        // Kategoriye ait makale varsa silinemez
        if ($category->articles()->count() > 0) {
            return redirect()->route('admin.article-categories.index')
                ->with('error', 'Bu kategoriye ait makaleler var. Önce makaleleri silmelisiniz.');
        }

        $category->delete();

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }
}
