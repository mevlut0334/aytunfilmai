@extends('layouts.admin')

@section('title', 'Yeni Paket Ekle - Admin Panel')
@section('page-title', 'Yeni Paket Ekle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> Paket Oluştur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.packages.store') }}" method="POST">
                        @csrf

                        <!-- Paket Adı -->
                        <div class="mb-3">
                            <label class="form-label">Paket Adı <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Örn: Standard Plan"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Açıklama -->
                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Paket açıklaması (opsiyonel)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Token Miktarı -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Token Miktarı <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="token_amount"
                                       class="form-control @error('token_amount') is-invalid @enderror"
                                       value="{{ old('token_amount') }}"
                                       min="1"
                                       placeholder="Örn: 660"
                                       required>
                                @error('token_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fiyat (referans) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fiyat (Referans) <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}"
                                       min="0.01"
                                       step="0.01"
                                       placeholder="Örn: 9.99"
                                       required>
                                <small class="text-muted">Gerçek fiyat Paddle dashboard'dan yönetilir.</small>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Paddle Price ID -->
                        <div class="mb-3">
                            <label class="form-label">
                                Paddle Price ID
                                <span class="badge bg-warning text-dark ms-1">Önemli</span>
                            </label>
                            <input type="text"
                                   name="paddle_price_id"
                                   class="form-control @error('paddle_price_id') is-invalid @enderror"
                                   value="{{ old('paddle_price_id') }}"
                                   placeholder="Örn: pri_01knkfaaydzefp75c9c3akhbhq">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Paddle dashboard → Catalog → Prices bölümünden alınır.
                                Girilmezse bu paket için Paddle ödemesi çalışmaz.
                            </small>
                            @error('paddle_price_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Aktif/Pasif -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Paket Aktif
                                </label>
                            </div>
                            <small class="text-muted">Pasif paketler kullanıcılara gösterilmez.</small>
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Geri Dön
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Paket Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
