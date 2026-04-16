@extends('layouts.admin')

@section('title', 'Yeni Mobil Slider Ekle')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h1 class="mt-4">
            <i class="bi bi-plus-circle"></i> Yeni Mobil Slider Ekle
        </h1>
        <p class="text-muted">Android ve iOS için yeni slider görseli ekleyin.</p>
    </div>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-body">
            <form action="{{ route('admin.mobile-sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Görsel -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Görsel <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp" required>
                    <div class="form-text">JPEG, PNG veya WebP — Max 5MB</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Önizleme -->
                <div class="mb-3" id="previewWrapper" style="display:none;">
                    <label class="form-label fw-semibold">Önizleme</label>
                    <div>
                        <img id="imagePreview" src="#" alt="Önizleme"
                             style="max-height: 200px; border-radius: 8px; border: 1px solid #dee2e6;">
                    </div>
                </div>

                <!-- Link -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Link</label>
                    <input type="text" name="link" class="form-control @error('link') is-invalid @enderror"
                           placeholder="https://... veya boş bırakın"
                           value="{{ old('link') }}">
                    @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Sıra -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sıra <span class="text-danger">*</span></label>
                    <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                           value="{{ old('order', 0) }}" min="0" required>
                    <div class="form-text">Küçük sayı önce gösterilir.</div>
                    @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Aktif -->
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active"
                               id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Kaydet
                    </button>
                    <a href="{{ route('admin.mobile-sliders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('input[name="image"]').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('imagePreview').src = ev.target.result;
            document.getElementById('previewWrapper').style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
@endsection
