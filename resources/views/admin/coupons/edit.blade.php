@extends('layouts.admin')

@section('title', 'Kupon Düzenle - Admin Panel')
@section('page-title', 'Kupon Düzenle: ' . $coupon->code)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Form Kartı -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Kupon Düzenle</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Kupon Kodu -->
                        <div class="mb-3">
                            <label class="form-label">Kupon Kodu <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $coupon->code) }}"
                                   placeholder="Örn: YILBASI2024"
                                   style="text-transform: uppercase;"
                                   required>
                            <small class="text-muted">Otomatik olarak büyük harfe çevrilir.</small>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- İndirim Tipi -->
                        <div class="mb-3">
                            <label class="form-label">İndirim Tipi <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="type"
                                           id="type_percentage"
                                           value="percentage"
                                           {{ old('type', $coupon->type) === 'percentage' ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="type_percentage">
                                        Yüzde (%)
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="type"
                                           id="type_fixed"
                                           value="fixed"
                                           {{ old('type', $coupon->type) === 'fixed' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_fixed">
                                        Sabit Tutar (₺)
                                    </label>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- İndirim Değeri -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">İndirim Değeri <span class="text-danger">*</span></label>
                                <input type="number"
                                       name="discount_value"
                                       class="form-control @error('discount_value') is-invalid @enderror"
                                       value="{{ old('discount_value', $coupon->discount_value) }}"
                                       min="0.01"
                                       step="0.01"
                                       placeholder="Örn: 10 veya 50"
                                       required>
                                <small class="text-muted">Yüzde için: 10 = %10, Sabit için: 50 = 50₺</small>
                                @error('discount_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Minimum Tutar -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Minimum Tutar (₺)</label>
                                <input type="number"
                                       name="min_amount"
                                       class="form-control @error('min_amount') is-invalid @enderror"
                                       value="{{ old('min_amount', $coupon->min_amount) }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Örn: 100">
                                <small class="text-muted">0 = Limit yok</small>
                                @error('min_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kullanım Limiti -->
                        <div class="mb-3">
                            <label class="form-label">Kullanım Limiti</label>
                            <input type="number"
                                   name="max_usage"
                                   class="form-control @error('max_usage') is-invalid @enderror"
                                   value="{{ old('max_usage', $coupon->max_usage) }}"
                                   min="1"
                                   placeholder="Boş bırakın = Sınırsız">
                            <small class="text-muted">Boş bırakılırsa sınırsız kullanım. Mevcut kullanım: {{ $coupon->usage_count ?? 0 }}</small>
                            @error('max_usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Başlangıç Tarihi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Geçerlilik Başlangıcı</label>
                                <input type="date"
                                       name="starts_at"
                                       class="form-control @error('starts_at') is-invalid @enderror"
                                       value="{{ old('starts_at', $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '') }}">
                                <small class="text-muted">Boş = Hemen geçerli</small>
                                @error('starts_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bitiş Tarihi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Geçerlilik Bitişi</label>
                                <input type="date"
                                       name="expires_at"
                                       class="form-control @error('expires_at') is-invalid @enderror"
                                       value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}">
                                <small class="text-muted">Boş = Süresiz</small>
                                @error('expires_at')
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
                                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kupon Aktif
                                </label>
                            </div>
                            <small class="text-muted">Pasif kuponlar kullanılamaz.</small>
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Geri Dön
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
