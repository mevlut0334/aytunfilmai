@extends('layouts.admin')

@section('title', 'Kategori Düzenle - Admin Panel')
@section('page-title', 'Kategori Düzenle')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.article-categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.article-categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Kategori Adı -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $category->name) }}"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                Slug (URL için)
                                <small class="text-muted">(Boş bırakılırsa otomatik oluşturulur)</small>
                            </label>
                            <input type="text"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug', $category->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Açıklama -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sıra -->
                        <div class="mb-3">
                            <label for="order" class="form-label">
                                Sıra
                                <small class="text-muted">(Gösterim sırası - küçükten büyüğe)</small>
                            </label>
                            <input type="number"
                                   class="form-control @error('order') is-invalid @enderror"
                                   id="order"
                                   name="order"
                                   value="{{ old('order', $category->order) }}"
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Aktif/Pasif -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kategori Aktif
                                </label>
                            </div>
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Güncelle
                            </button>
                            <a href="{{ route('admin.article-categories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
