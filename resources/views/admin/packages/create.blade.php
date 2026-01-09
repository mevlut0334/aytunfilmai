@extends('layouts.admin')

@section('title', 'Yeni Paket Ekle - Admin Panel')
@section('page-title', 'Yeni Paket Ekle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Form Kartı -->
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
                                   placeholder="Örn: Standart Paket"
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
                                       placeholder="Örn: 500"
                                       required>
                                @error('token_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fiyat -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fiyat (₺) <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}"
                                       min="0.01"
                                       step="0.01"
                                       placeholder="Örn: 149.90"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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

                        <!-- Bilgilendirme -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Paketler fiyata göre otomatik sıralanır (ucuzdan pahalıya).
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
