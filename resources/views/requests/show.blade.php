@extends('layouts.app')

@section('title', 'Talep Detayı - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık ve Geri Dön -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Taleplerime Dön
            </a>
            <h2><i class="bi bi-film"></i> {{ $request->title }}</h2>
        </div>
    </div>

    <div class="row">
        <!-- Sol: Talep Bilgileri -->
        <div class="col-lg-8 mb-4">
            <!-- Durum Kartı -->
            <div class="card shadow-sm mb-3">
                <div class="card-header
                    @if($request->status === 'completed') bg-success
                    @elseif($request->status === 'processing') bg-info
                    @elseif($request->status === 'pending') bg-warning
                    @else bg-danger
                    @endif text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Talep Durumu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Durum</small>
                            <h5>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning text-dark">Beklemede</span>
                                @elseif($request->status === 'processing')
                                    <span class="badge bg-info text-dark">İşleniyor</span>
                                @elseif($request->status === 'completed')
                                    <span class="badge bg-success">Tamamlandı</span>
                                @else
                                    <span class="badge bg-danger">Başarısız</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Oluşturma Tarihi</small>
                            <h5>{{ $request->created_at->format('d.m.Y H:i') }}</h5>
                        </div>
                    </div>

                    @if($request->status === 'failed' && $request->error_message)
                        <div class="alert alert-danger mt-3 mb-0">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Hata:</strong>
                            <p class="mb-0 mt-2">{{ $request->error_message }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Video (Eğer tamamlandıysa) -->
@if($request->status === 'completed' && $request->video_url)
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Film Hazır!</h5>
        </div>
        <div class="card-body text-center py-5">
            <i class="bi bi-film display-1 text-success mb-4"></i>
            <h4 class="mb-4">Filminiz başarıyla oluşturuldu!</h4>
            <a href="{{ $request->video_url }}" class="btn btn-success btn-lg" download>
                <i class="bi bi-download"></i> Filmi İndir
            </a>
        </div>
    </div>
@endif

            <!-- Karakterler -->
            @if($request->characters->count() > 0)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Karakterler ({{ $request->characters->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($request->characters as $character)
                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6 class="mb-3">
                                    <i class="bi bi-person-fill"></i> {{ $character->name }}
                                </h6>

                                <!-- Karakter Görselleri -->
                                <div class="row g-2">
                                    @foreach($character->images as $image)
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <a href="{{ asset('storage/' . $image->image_path) }}"
                                               data-lightbox="character-{{ $character->id }}"
                                               data-title="{{ $character->name }} - Görsel {{ $image->order }}">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                     class="img-fluid rounded shadow-sm"
                                                     alt="{{ $character->name }} - {{ $image->order }}"
                                                     style="cursor: pointer; height: 150px; width: 100%; object-fit: cover;">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    {{ $character->images->count() }} görsel
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Açıklama -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-textarea-t"></i> Film Açıklaması</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $request->description }}</p>
                </div>
            </div>
        </div>

        <!-- Sağ: İşlem ve Bilgi -->
        <div class="col-lg-4">
            <!-- İşlemler -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-gear"></i> İşlemler</h6>
                </div>
                <div class="card-body">
                    @if($request->status === 'pending' || $request->status === 'failed')
                        <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                              onsubmit="return confirm('Bu talebi silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash"></i> Talebi Sil
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="bi bi-info-circle-fill"></i>
                                {{ $request->status === 'processing' ? 'İşlem devam ederken' : 'Tamamlanmış' }} talepler silinemez.
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- İstatistikler -->
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-info-square-fill text-info"></i> Talep Bilgileri</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2">
                            <strong>Talep ID:</strong> #{{ $request->id }}
                        </li>
                        <li class="mb-2">
                            <strong>Karakter Sayısı:</strong> {{ $request->characters->count() }}
                        </li>
                        <li class="mb-2">
                            <strong>Toplam Görsel:</strong> {{ $request->totalImages }}
                        </li>
                        <li class="mb-0">
                            <strong>Son Güncelleme:</strong> {{ $request->updated_at->format('d.m.Y H:i') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- Lightbox CSS (Görsel büyütme için) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<!-- Lightbox JS (Görsel büyütme için) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush
@endsection
