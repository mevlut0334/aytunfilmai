@extends('layouts.app')

@section('title', 'Ana Sayfa - Aytun Film AI')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="bi bi-film"></i> Aytun Film AI
                </h1>
                <p class="lead mb-4">
                    Yapay zeka destekli film üretim platformuna hoş geldiniz.
                    Hayalinizdeki filmi oluşturmak için hemen başlayın!
                </p>
                <div class="d-grid gap-2 d-md-flex">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-person-plus"></i> Hemen Kayıt Ol
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right"></i> Giriş Yap
                        </a>
                    @else
                        <a href="{{ route('user.profile') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-person-circle"></i> Profilime Git
                        </a>
                        <a href="#" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-plus-circle"></i> Yeni Film Talebi
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="text-center">
                    <i class="bi bi-camera-reels display-1 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row text-center mb-5">
        <div class="col-12">
            <h2 class="mb-4">Nasıl Çalışır?</h2>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-plus-fill display-3 text-primary"></i>
                    </div>
                    <h5 class="card-title">1. Kayıt Olun</h5>
                    <p class="card-text text-muted">
                        Hızlı ve kolay kayıt işlemi ile platformumuza katılın.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-coin display-3 text-success"></i>
                    </div>
                    <h5 class="card-title">2. Token Satın Alın</h5>
                    <p class="card-text text-muted">
                        İhtiyacınıza uygun token paketini seçin ve satın alın.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 text-center border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <i class="bi bi-film display-3 text-info"></i>
                    </div>
                    <h5 class="card-title">3. Film Oluşturun</h5>
                    <p class="card-text text-muted">
                        Senaryonuzu yükleyin, karakterlerinizi ekleyin ve filminizi oluşturun.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-primary text-white py-5 mt-5">
    <div class="container text-center">
        <h2 class="mb-4">Hemen Başlayın!</h2>
        <p class="lead mb-4">
            Hayalinizdeki film projesini gerçekleştirmenin tam zamanı.
        </p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                <i class="bi bi-arrow-right-circle"></i> Ücretsiz Kayıt Ol
            </a>
        @endguest
    </div>
</div>
@endsection
