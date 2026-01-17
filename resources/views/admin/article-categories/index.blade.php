@extends('layouts.admin')

@section('title', 'Makale Kategorileri - Admin Panel')
@section('page-title', 'Makale Kategorileri')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Makale Kategorileri</h4>
                <a href="{{ route('admin.article-categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Yeni Kategori Ekle
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">Sıra</th>
                                        <th>Kategori Adı</th>
                                        <th>Slug</th>
                                        <th style="width: 120px;">Makale Sayısı</th>
                                        <th style="width: 100px;">Durum</th>
                                        <th style="width: 150px;" class="text-end">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $category->order }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $category->name }}</strong>
                                                @if($category->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($category->description, 60) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $category->slug }}</code>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $category->articles_count }} Makale</span>
                                            </td>
                                            <td>
                                                @if($category->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.article-categories.edit', $category->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Düzenle">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.article-categories.destroy', $category->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')">
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
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-folder-x" style="font-size: 4rem; color: #ccc;"></i>
                            <p class="mt-3 text-muted">Henüz kategori eklenmemiş.</p>
                            <a href="{{ route('admin.article-categories.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> İlk Kategoriyi Ekle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
