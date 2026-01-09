@extends('layouts.admin')

@section('title', 'Talep Detayı - Admin Panel')
@section('page-title', 'Talep Detayı #' . $request->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sol: Talep Bilgileri -->
        <div class="col-lg-8 mb-4">
            <!-- Genel Bilgiler -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Talep Bilgileri</h6>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $request->title }}</h4>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Talep ID:</strong> #{{ $request->id }}
                        </div>
                        <div class="col-md-6">
                            <strong>Kullanıcı:</strong> {{ $request->user->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Oluşturma Tarihi:</strong> {{ $request->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Karakter Sayısı:</strong> {{ $request->characters->count() }}
                        </div>
                    </div>
                    <div class="mb-0">
                        <strong>Açıklama:</strong>
                        <p class="mt-2 mb-0" style="white-space: pre-wrap;">{{ $request->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Video (Eğer tamamlandıysa) -->
            @if($request->status === 'completed' && $request->video_url)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-play-circle"></i> Üretilen Film</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Video URL:</strong><br>
                            <a href="{{ $request->video_url }}" target="_blank" class="text-break">
                                {{ $request->video_url }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Hata Mesajı (Eğer başarısızsa) -->
            @if($request->status === 'failed' && $request->error_message)
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Hata Mesajı</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $request->error_message }}</p>
                    </div>
                </div>
            @endif

            <!-- Karakterler -->
            @if($request->characters->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bi bi-people"></i> Karakterler ({{ $request->characters->count() }})</h6>
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
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                 class="img-fluid rounded shadow-sm"
                                                 alt="{{ $character->name }} - {{ $image->order }}"
                                                 style="height: 150px; width: 100%; object-fit: cover;">
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
        </div>

        <!-- Sağ: Durum ve İşlemler -->
        <div class="col-lg-4">
            <!-- Mevcut Durum -->
            <div class="card shadow-sm mb-3">
                <div class="card-header
                    @if($request->status === 'completed') bg-success
                    @elseif($request->status === 'processing') bg-info
                    @elseif($request->status === 'pending') bg-warning
                    @else bg-danger
                    @endif text-white">
                    <h6 class="mb-0"><i class="bi bi-clipboard-check"></i> Mevcut Durum</h6>
                </div>
                <div class="card-body text-center">
                    @if($request->status === 'pending')
                        <i class="bi bi-clock display-3 text-warning"></i>
                        <h5 class="mt-3">Beklemede</h5>
                    @elseif($request->status === 'processing')
                        <i class="bi bi-arrow-repeat display-3 text-info"></i>
                        <h5 class="mt-3">İşleniyor</h5>
                    @elseif($request->status === 'completed')
                        <i class="bi bi-check-circle display-3 text-success"></i>
                        <h5 class="mt-3">Tamamlandı</h5>
                    @else
                        <i class="bi bi-x-circle display-3 text-danger"></i>
                        <h5 class="mt-3">Başarısız</h5>
                    @endif

                    @if($request->processedBy)
                        <hr>
                        <div class="text-start">
                            <small class="text-muted">İşleyen Admin:</small><br>
                            <strong>{{ $request->processedBy->name }}</strong><br>
                            <small class="text-muted">{{ $request->processed_at->format('d.m.Y H:i') }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Durum Güncelleme -->
            @if($request->status !== 'completed')
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="bi bi-pencil-square"></i> Durum Güncelle</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.requests.update-status', $request->id) }}" method="POST" id="statusForm">
                            @csrf

                            <!-- Durum Seçimi -->
                            <div class="mb-3">
                                <label class="form-label">Yeni Durum</label>
                                <select name="status" id="statusSelect" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    @if($request->status === 'pending')
                                        <option value="processing">İşleme Al</option>
                                        <option value="failed">Başarısız</option>
                                    @elseif($request->status === 'processing')
                                        <option value="completed">Tamamlandı</option>
                                        <option value="failed">Başarısız</option>
                                    @elseif($request->status === 'failed')
                                        <option value="processing">Tekrar İşle</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Completed Alanları -->
                            <div id="completedFields" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Video URL <span class="text-danger">*</span></label>
                                    <input type="url" name="video_url" class="form-control" placeholder="https://...">
                                    <small class="text-muted">Kullanıcı bu linki indirebilecek</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Düşülecek Token <span class="text-danger">*</span></label>
                                    <input type="number" name="token_amount" class="form-control" min="1" placeholder="Örn: 100">
                                    <small class="text-muted">
                                        Kullanıcı bakiyesi: <strong>{{ number_format($request->user->token_balance, 0) }}</strong> token
                                    </small>
                                </div>

                                <div class="alert alert-info mb-3">
                                    <small>
                                        <i class="bi bi-info-circle"></i>
                                        Token otomatik olarak <strong>{{ $request->user->name }}</strong> kullanıcısından düşülecek.
                                    </small>
                                </div>
                            </div>

                            <!-- Failed Alanları -->
                            <div id="failedFields" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Hata Mesajı <span class="text-danger">*</span></label>
                                    <textarea name="error_message" class="form-control" rows="4" placeholder="Neden başarısız oldu?"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> Durumu Güncelle
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Geri Dön -->
            <div class="mt-3">
                <a href="{{ route('admin.requests.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Talep Listesine Dön
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Durum değiştiğinde ilgili alanları göster/gizle
    document.getElementById('statusSelect').addEventListener('change', function() {
        const status = this.value;
        const completedFields = document.getElementById('completedFields');
        const failedFields = document.getElementById('failedFields');

        // Hepsini gizle
        completedFields.style.display = 'none';
        failedFields.style.display = 'none';

        // İlgili alanı göster
        if (status === 'completed') {
            completedFields.style.display = 'block';
            // Required ekle
            document.querySelector('[name="video_url"]').required = true;
            document.querySelector('[name="token_amount"]').required = true;
            document.querySelector('[name="error_message"]').required = false;
        } else if (status === 'failed') {
            failedFields.style.display = 'block';
            // Required ekle
            document.querySelector('[name="error_message"]').required = true;
            document.querySelector('[name="video_url"]').required = false;
            document.querySelector('[name="token_amount"]').required = false;
        } else {
            // Processing - hiçbir alan gerekmez
            document.querySelector('[name="video_url"]').required = false;
            document.querySelector('[name="token_amount"]').required = false;
            document.querySelector('[name="error_message"]').required = false;
        }
    });
</script>
@endpush
@endsection
