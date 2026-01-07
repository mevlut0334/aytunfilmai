@extends('layouts.app')

@section('title', 'Profili Düzenle - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Profili Düzenle</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Ad Soyad -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- E-posta -->
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+90 555 123 4567"
                                   required>
                            <small class="form-text text-muted">Sadece rakam ve + işareti kullanabilirsiniz.</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Şifre Değiştir (Opsiyonel)</h5>
                        <p class="text-muted small">Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakabilirsiniz.</p>

                        <!-- Yeni Şifre -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Yeni Şifre</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password">
                            <small class="form-text text-muted">En az 8 karakter olmalıdır.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Şifre Onay -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Yeni Şifre Onay</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation">
                        </div>

                        <!-- Butonlar -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> İptal
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
