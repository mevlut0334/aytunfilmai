@extends('layouts.admin')

@section('title', 'Makaleler - Admin Panel')
@section('page-title', 'Makaleler')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0">Makaleler</h4>
                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Yeni Makale Ekle
                </a>
            </div>
        </div>
    </div>

    <!-- Filtreleme -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.articles.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   placeholder="Makale ara..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Tüm Durumlar</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Tüm Kategoriler</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filtrele
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Makale Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">Sıra</th>
                                        <th style="width: 80px;">Görsel</th>
                                        <th>Başlık</th>
                                        <th style="width: 150px;">Kategori</th>
                                        <th style="width: 120px;">Durum</th>
                                        <th style="width: 100px;">Görüntülenme</th>
                                        <th style="width: 150px;">Yayın Tarihi</th>
                                        <th style="width: 180px;" class="text-end">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $article->order }}</span>
                                            </td>
                                            <td>
                                                @if($article->featured_image)
                                                    <img src="{{ asset('storage/' . $article->featured_image) }}"
                                                         alt="{{ $article->title }}"
                                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                @else
                                                    <div style="width: 60px; height: 60px; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($article->title, 50) }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i> {{ $article->user->name }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($article->category)
                                                    <span class="badge bg-info">{{ $article->category->name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Kategorisiz</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($article->status == 'published')
                                                    @if($article->published_at && $article->published_at->isFuture())
                                                        <span class="badge bg-warning">Zamanlanmış</span>
                                                    @else
                                                        <span class="badge bg-success">Yayında</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Taslak</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $article->views }}</span>
                                            </td>
                                            <td>
                                                @if($article->published_at)
                                                    <small>{{ $article->published_at->format('d.m.Y H:i') }}</small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($article->isPublished())
                                                    <a href="{{ route('articles.show', $article->slug) }}"
                                                       class="btn btn-sm btn-info"
                                                       target="_blank"
                                                       title="Görüntüle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.articles.edit', $article->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Düzenle">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.articles.destroy', $article->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bu makaleyi silmek istediğinizden emin misiniz?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Sil">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-newspaper" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="mt-3 text-muted">Henüz makale eklenmemiş.</p>
                            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> İlk Makaleyi Ekle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
