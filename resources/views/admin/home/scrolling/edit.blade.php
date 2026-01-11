@extends('layouts.admin')

@section('title', 'Görsel Düzenle')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-pencil"></i> Görsel Düzenle
        </h1>
        <a href="{{ route('admin.home.scrolling') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <!-- Sol: Form -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Görsel Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.home.scrolling.update', $image->id) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Başlık -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Başlık <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   placeholder="Görsel başlığı"
                                   value="{{ old('title', $image->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Görsel -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Görsel</label>

                            @if($image->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $image->image) }}"
                                         alt="Mevcut görsel"
                                         class="img-thumbnail"
                                         style="max-height: 200px;">
                                    <p class="text-muted small mt-1">Yeni görsel yüklerseniz eskisi silinir.</p>
                                </div>
                            @endif

                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/jpeg,image/jpg,image/png,image/webp">
                            <small class="text-muted">Max 5MB, Format: JPG, PNG, WEBP | Önerilen boyut: 400x300px</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Link -->
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text"
                                   class="form-control @error('link') is-invalid @enderror"
                                   id="link"
                                   name="link"
                                   placeholder="/packages veya https://..."
                                   value="{{ old('link', $image->link) }}">
                            <small class="text-muted">Görsele tıklandığında gidilecek sayfa (opsiyonel)</small>
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sıra -->
                        <div class="mb-3">
                            <label for="order" class="form-label">
                                Sıra <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('order') is-invalid @enderror"
                                   id="order"
                                   name="order"
                                   min="0"
                                   value="{{ old('order', $image->order) }}"
                                   required>
                            <small class="text-muted">Küçük numara önce gösterilir</small>
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
                                       {{ old('is_active', $image->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif (Ana sayfada göster)
                                </label>
                            </div>
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.home.scrolling') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ: Bilgi Kartı -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle-fill text-info"></i> Görsel Bilgisi
                    </h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <strong>Durum:</strong>
                            @if($image->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>Oluşturulma:</strong> {{ $image->created_at->format('d.m.Y H:i') }}
                        </li>
                        <li class="mb-0">
                            <strong>Son Güncelleme:</strong> {{ $image->updated_at->format('d.m.Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tasarım Önerileri -->
            <div class="card bg-light mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-palette text-warning"></i> Tasarım Önerileri
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Film afişleri veya prodüksiyon görselleri</li>
                        <li class="mb-2">Neon efektli, modern tasarımlar</li>
                        <li class="mb-2">Kare veya dikdörtgen formatlar</li>
                        <li class="mb-0">Yüksek kontrast, canlı renkler</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
