@extends('layouts.app')

@section('title', 'Profilim - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <i class="bi bi-person-circle display-1 text-primary mb-3"></i>
                    <h2 class="mb-3">Hoş Geldiniz, {{ $user->name }}!</h2>
                    <p class="text-muted mb-4">
                        <i class="bi bi-envelope"></i> {{ $user->email }} |
                        <i class="bi bi-phone"></i> {{ $user->phone }}
                    </p>

                    <!-- Token Bakiyesi -->
                    <div class="mb-4">
                        <div class="d-inline-block bg-gradient-primary text-white p-4 rounded-3">
                            <h6 class="mb-2">Token Bakiyeniz</h6>
                            <h1 class="display-3 mb-0">
                                <i class="bi bi-coin"></i> {{ number_format($user->token_balance, 0) }}
                            </h1>
                        </div>
                    </div>

                    <!-- Hızlı İşlemler -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('user.edit') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-pencil"></i> Profili Düzenle
                        </a>
                        <a href="#" class="btn btn-success btn-lg">
                            <i class="bi bi-plus-circle"></i> Yeni Film Talebi
                        </a>
                        <a href="#" class="btn btn-info btn-lg text-white">
                            <i class="bi bi-bag-plus"></i> Token Satın Al
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kullanıcı Bilgileri -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Hesap Bilgileri</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Ad Soyad:</strong></span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>E-posta:</strong></span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Telefon:</strong></span>
                            <span>{{ $user->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Kayıt Tarihi:</strong></span>
                            <span>{{ $user->created_at->format('d.m.Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Hesap Tipi:</strong></span>
                            <span>
                                @if($user->is_admin)
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-success">Kullanıcı</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-coin"></i> Token Bilgileri</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="display-4 text-success mb-3">
                        {{ number_format($user->token_balance, 0) }}
                    </h2>
                    <p class="text-muted mb-4">Mevcut Token Bakiyeniz</p>
                    <a href="#" class="btn btn-success">
                        <i class="bi bi-bag-plus"></i> Token Satın Al
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bilgi Mesajı -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle-fill"></i> Bilgilendirme</h5>
                <p class="mb-0">
                    Hesabınız başarıyla oluşturuldu! Film talebi oluşturmak için token satın almanız gerekmektedir.
                    Profil bilgilerinizi düzenlemek için "Profili Düzenle" butonuna tıklayabilirsiniz.
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush
@endsection
