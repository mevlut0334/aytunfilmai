@extends('layouts.admin')

@section('title', 'Yeni Slider Ekle')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-plus-circle"></i> Yeni Slider Ekle
        </h1>
        <a href="{{ route('admin.home.sliders') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <!-- Sol: Form -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Slider Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.home.sliders.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <!-- Başlık -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Başlık</label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   placeholder="Slider başlığı (opsiyonel)"
                                   value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Görsel -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                Görsel <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   required>
                            <small class="text-muted">Max 5MB, Format: JPG, PNG, WEBP | Önerilen boyut: 1920x600px</small>
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
                                   value="{{ old('link') }}">
                            <small class="text-muted">Slider'a tıklandığında gidilecek sayfa (opsiyonel)</small>
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
                                   value="{{ old('order', 0) }}"
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
                                       {{ old('is_active', true) ? 'checked' : '' }}>
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
                            <a href="{{ route('admin.home.sliders') }}" class="btn btn-secondary">
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
                        <i class="bi bi-info-circle-fill text-info"></i> İpuçları
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">
                            <strong>Başlık:</strong> Slider üzerinde gösterilecek metin (opsiyonel)
                        </li>
                        <li class="mb-2">
                            <strong>Görsel:</strong> 1920x600px boyutunda görsel önerilir
                        </li>
                        <li class="mb-2">
                            <strong>Link:</strong> Tıklandığında yönlendirilecek sayfa
                        </li>
                        <li class="mb-0">
                            <strong>Sıra:</strong> Gösterim sırasını belirler (0, 1, 2...)
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Görsel Öneri -->
            <div class="card bg-light mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-image text-primary"></i> Görsel Önerileri
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Yüksek çözünürlüklü görsel kullanın</li>
                        <li class="mb-2">Ana mesajınız görsel merkezinde olsun</li>
                        <li class="mb-2">Neon renkler kullanarak tasarımı parlak yapın</li>
                        <li class="mb-0">Mobil uyumlu boyut seçin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
