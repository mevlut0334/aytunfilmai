@extends('layouts.app')

@section('title', 'Taleplerim - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-film"></i> Film Taleplerim</h2>
            <a href="{{ route('requests.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Yeni Talep
            </a>
        </div>
    </div>

    @if($requests->isEmpty())
        <!-- Boş Durum -->
        <div class="row">
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-film display-1 text-muted"></i>
                        <h3 class="mt-4">Henüz Talebiniz Yok</h3>
                        <p class="text-muted mb-4">Film talebi oluşturarak yapay zeka ile film üretmeye başlayabilirsiniz.</p>
                        <a href="{{ route('requests.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> İlk Talebinizi Oluşturun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Talep Listesi -->
        <div class="row">
            @foreach($requests as $request)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <!-- Durum Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            @if($request->status === 'pending')
                                <span class="badge bg-warning text-dark">Beklemede</span>
                            @elseif($request->status === 'processing')
                                <span class="badge bg-info text-dark">İşleniyor</span>
                            @elseif($request->status === 'completed')
                                <span class="badge bg-success">Tamamlandı</span>
                            @else
                                <span class="badge bg-danger">Başarısız</span>
                            @endif
                        </div>

                        <!-- Thumbnail (Eğer karakter varsa ilk görsel) -->
                        @if($request->characters->count() > 0 && $request->characters->first()->images->count() > 0)
                            <img src="{{ asset('storage/' . $request->characters->first()->images->first()->image_path) }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $request->title }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="bi bi-film display-3 text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <!-- Başlık -->
                            <h5 class="card-title">{{ Str::limit($request->title, 50) }}</h5>

                            <!-- Açıklama -->
                            <p class="card-text text-muted small">
                                {{ Str::limit($request->description, 100) }}
                            </p>

                            <!-- Bilgiler -->
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> {{ $request->created_at->format('d.m.Y H:i') }}
                                </small>
                                @if($request->characters->count() > 0)
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-people"></i> {{ $request->characters->count() }} Karakter
                                    </small>
                                @endif
                            </div>

                            <!-- Butonlar -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Detay
                                </a>

                                @if($request->status === 'pending' || $request->status === 'failed')
                                    <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                                          onsubmit="return confirm('Bu talebi silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="bi bi-trash"></i> Sil
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('styles')
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush
@endsection
