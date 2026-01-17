@extends('layouts.admin')

@section('title', 'Yeni Makale Ekle - Admin Panel')
@section('page-title', 'Yeni Makale Ekle')

@push('styles')
<!-- CKEditor CDN (Ücretsiz) -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Sol Kolon - Ana İçerik -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Makale İçeriği</h5>
                    </div>
                    <div class="card-body">
                        <!-- Başlık -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Başlık <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   required
                                   autofocus>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                Slug (URL)
                                <small class="text-muted">(Boş bırakılırsa otomatik oluşturulur)</small>
                            </label>
                            <input type="text"
                                   class="form-control @error('slug') is-invalid @enderror"
                                   id="slug"
                                   name="slug"
                                   value="{{ old('slug') }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Özet -->
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">
                                Kısa Özet
                                <small class="text-muted">(Listeleme sayfalarında gösterilir)</small>
                            </label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                      id="excerpt"
                                      name="excerpt"
                                      rows="3">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- İçerik -->
                        <div class="mb-3">
                            <label for="content" class="form-label">İçerik <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      name="content"
                                      rows="20">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Ayarları -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">SEO Ayarları</h5>
                    </div>
                    <div class="card-body">
                        <!-- Meta Title -->
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">
                                Meta Başlık
                                <small class="text-muted">(Max 60 karakter)</small>
                            </label>
                            <input type="text"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title') }}"
                                   maxlength="60">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa makale başlığı kullanılır</small>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">
                                Meta Açıklama
                                <small class="text-muted">(Max 160 karakter)</small>
                            </label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      maxlength="160">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">
                                Anahtar Kelimeler
                                <small class="text-muted">(Virgülle ayırın)</small>
                            </label>
                            <input type="text"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   value="{{ old('meta_keywords') }}">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon - Ayarlar -->
            <div class="col-lg-4">
                <!-- Yayınlama Ayarları -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Yayınlama</h5>
                    </div>
                    <div class="card-body">
                        <!-- Durum -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Durum <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Yayında</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Yayın Tarihi -->
                        <div class="mb-3">
                            <label for="published_at" class="form-label">
                                Yayın Tarihi
                                <small class="text-muted">(Gelecek tarih: zamanlanmış)</small>
                            </label>
                            <input type="datetime-local"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   id="published_at"
                                   name="published_at"
                                   value="{{ old('published_at') }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Boş bırakılırsa şimdi yayınlanır</small>
                        </div>

                        <!-- Sıra -->
                        <div class="mb-3">
                            <label for="order" class="form-label">
                                Sıra
                                <small class="text-muted">(Gösterim sırası)</small>
                            </label>
                            <input type="number"
                                   class="form-control @error('order') is-invalid @enderror"
                                   id="order"
                                   name="order"
                                   value="{{ old('order', 0) }}"
                                   min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Kategori</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id"
                                name="category_id">
                            <option value="">Kategorisiz</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Öne Çıkan Görsel -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Öne Çıkan Görsel</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="file"
                                   class="form-control @error('featured_image') is-invalid @enderror"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max 2MB (jpeg, jpg, png, webp)</small>
                        </div>

                        <!-- Görsel Önizleme -->
                        <div id="imagePreview" style="display: none;">
                            <img id="preview" src="" style="width: 100%; border-radius: 8px;">
                        </div>

                        <!-- Alt Text -->
                        <div class="mb-0">
                            <label for="image_alt" class="form-label">
                                Alt Text (SEO için)
                            </label>
                            <input type="text"
                                   class="form-control @error('image_alt') is-invalid @enderror"
                                   id="image_alt"
                                   name="image_alt"
                                   value="{{ old('image_alt') }}">
                            @error('image_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kaydet Butonu -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Makaleyi Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// CKEditor - Türkçe Dil Desteği ile
ClassicEditor
    .create(document.querySelector('#content'), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ]
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Başlık 1', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Başlık 2', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Başlık 3', class: 'ck-heading_heading4' }
            ]
        }
    })
    .catch(error => {
        console.error(error);
    });

// Görsel Önizleme
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
