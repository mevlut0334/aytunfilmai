@extends('layouts.admin')

@section('title', 'Yeni Admin Ekle - Admin Panel')
@section('page-title', 'Yeni Admin Ekle')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Form Kartı -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus"></i> Admin Kullanıcı Oluştur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admins.store') }}" method="POST">
                        @csrf

                        <!-- Ad Soyad -->
                        <div class="mb-3">
                            <label class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Örn: Ahmet Yılmaz"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- E-posta -->
                        <div class="mb-3">
                            <label class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="Örn: admin@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div class="mb-3">
                            <label class="form-label">Telefon <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}"
                                   placeholder="Örn: 05xxxxxxxxx"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Şifre -->
                        <div class="mb-3">
                            <label class="form-label">Şifre <span class="text-danger">*</span></label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="En az 8 karakter"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Şifre en az 8 karakter olmalıdır.</small>
                        </div>

                        <!-- Şifre Tekrar -->
                        <div class="mb-4">
                            <label class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Şifrenizi tekrar girin"
                                   required>
                        </div>

                        <!-- Bilgilendirme -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Not:</strong> Oluşturduğunuz admin kullanıcı, tüm admin panel özelliklerine erişebilecektir.
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Geri Dön
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Admin Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
