@extends('layouts.admin')

@section('title', 'Site Ayarları - Ana Sayfa')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-gear-fill"></i> Site Ayarları
        </h1>
        <a href="{{ route('admin.home.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <!-- Sol: Form -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Genel Ayarlar</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.home.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- WhatsApp Numarası -->
                        <div class="mb-4">
                            <label for="whatsapp_number" class="form-label">
                                WhatsApp Numarası <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-whatsapp text-success"></i>
                                </span>
                                <input type="text"
                                       class="form-control @error('whatsapp_number') is-invalid @enderror"
                                       id="whatsapp_number"
                                       name="whatsapp_number"
                                       placeholder="+905551234567"
                                       value="{{ old('whatsapp_number', $whatsappNumber) }}"
                                       required>
                            </div>
                            <small class="text-muted">
                                Örnek: +905551234567 (Ülke kodu ile birlikte)
                            </small>
                            @error('whatsapp_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.home.index') }}" class="btn btn-secondary">
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
                        <i class="bi bi-info-circle-fill text-info"></i> Bilgilendirme
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">
                            <strong>WhatsApp Numarası:</strong> Footer'daki sticky bar'da gösterilir
                        </li>
                        <li class="mb-2">
                            Format: <code>+90XXXXXXXXXX</code>
                        </li>
                        <li class="mb-0">
                            Tıklandığında WhatsApp konuşması başlatır
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Önizleme -->
            <div class="card bg-light mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-eye text-primary"></i> Önizleme
                    </h6>
                    <p class="small text-muted mb-2">Sticky Footer Bar'da şöyle görünecek:</p>
                    <div class="border rounded p-2 bg-white text-center">
                        <a href="#" class="text-decoration-none">
                            <i class="bi bi-whatsapp text-success"></i>
                            <span class="ms-1">WhatsApp Destek</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
