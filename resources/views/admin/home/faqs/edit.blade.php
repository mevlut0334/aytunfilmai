@extends('layouts.admin')

@section('title', 'SSS Düzenle')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-pencil"></i> SSS Düzenle
        </h1>
        <a href="{{ route('admin.home.faqs') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">SSS Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.home.faqs.update', $faq->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h6 class="text-muted mb-3"><i class="bi bi-flag"></i> Türkçe (Zorunlu)</h6>

                        <div class="mb-3">
                            <label for="question" class="form-label">
                                Soru <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('question') is-invalid @enderror"
                                   id="question"
                                   name="question"
                                   placeholder="Sıkça sorulan soru"
                                   value="{{ old('question', $faq->question) }}"
                                   required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="answer" class="form-label">
                                Cevap <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('answer') is-invalid @enderror"
                                      id="answer"
                                      name="answer"
                                      rows="4"
                                      placeholder="Sorunun detaylı cevabı"
                                      required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3"><i class="bi bi-translate"></i> İngilizce Çeviri (İsteğe Bağlı)</h6>

                        <div class="mb-3">
                            <label class="form-label">Soru (EN)</label>
                            <input type="text"
                                   class="form-control"
                                   name="translations[en][question]"
                                   value="{{ old('translations.en.question', $faq->translations['en']['question'] ?? '') }}"
                                   placeholder="English question">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cevap (EN)</label>
                            <textarea class="form-control"
                                      name="translations[en][answer]"
                                      rows="4"
                                      placeholder="English answer">{{ old('translations.en.answer', $faq->translations['en']['answer'] ?? '') }}</textarea>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="order" class="form-label">
                                Sıra <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('order') is-invalid @enderror"
                                   id="order"
                                   name="order"
                                   min="0"
                                   value="{{ old('order', $faq->order) }}"
                                   required>
                            <small class="text-muted">Küçük numara önce gösterilir</small>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif (Ana sayfada göster)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.home.faqs') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle-fill text-info"></i> SSS Bilgisi
                    </h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <strong>Durum:</strong>
                            @if($faq->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Pasif</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>İngilizce:</strong>
                            @if(!empty($faq->translations['en']['question']))
                                <span class="badge bg-success">Mevcut</span>
                            @else
                                <span class="badge bg-warning text-dark">Eksik</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>Oluşturulma:</strong> {{ $faq->created_at->format('d.m.Y H:i') }}
                        </li>
                        <li class="mb-0">
                            <strong>Son Güncelleme:</strong> {{ $faq->updated_at->format('d.m.Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card bg-light mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-lightbulb text-warning"></i> İpuçları
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2"><strong>Türkçe:</strong> Zorunlu alan</li>
                        <li class="mb-0"><strong>İngilizce:</strong> Girilmezse Türkçe gösterilir</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
