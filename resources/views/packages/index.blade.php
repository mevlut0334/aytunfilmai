@extends('layouts.app')

@section('title', 'Token Paketleri - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4 mb-3">
                <i class="bi bi-coin text-warning"></i> Token Paketleri
            </h1>
            <p class="lead text-muted">
                Film üretmek için ihtiyacınız olan token paketini seçin ve satın alın.
            </p>
        </div>
    </div>

    @if($packages->isEmpty())
        <!-- Boş Durum -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle display-4"></i>
                    <h4 class="mt-3">Şu anda aktif paket bulunmamaktadır.</h4>
                    <p class="mb-0">Lütfen daha sonra tekrar kontrol edin.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Paket Kartları -->
        <div class="row g-4">
            @foreach($packages as $package)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-card">
                        <div class="card-body d-flex flex-column">
                            <!-- Paket Adı -->
                            <h3 class="card-title text-center mb-3">
                                {{ $package->name }}
                            </h3>

                            <!-- Token Miktarı -->
                            <div class="text-center mb-3">
                                <div class="display-3 text-warning">
                                    <i class="bi bi-coin"></i>
                                </div>
                                <h2 class="text-primary mb-0">
                                    {{ number_format($package->token_amount, 0) }}
                                </h2>
                                <p class="text-muted">Token</p>
                            </div>

                            <!-- Açıklama -->
                            @if($package->description)
                                <p class="text-muted text-center mb-3">
                                    {{ $package->description }}
                                </p>
                            @endif

                            <!-- Fiyat -->
                            <div class="text-center mb-4">
                                <h3 class="text-success mb-0">
                                    {{ number_format($package->price, 2) }} ₺
                                </h3>
                            </div>

                            <!-- Sepete Ekle Formu -->
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">

                                <!-- Miktar Seçimi -->
                                <div class="mb-3">
                                    <label for="quantity_{{ $package->id }}" class="form-label">Miktar</label>
                                    <select name="quantity"
                                            id="quantity_{{ $package->id }}"
                                            class="form-select">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Sepete Ekle Butonu -->
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-cart-plus"></i> Sepete Ekle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bilgilendirme -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-info-circle-fill text-info"></i> Token Nasıl Kullanılır?
                        </h5>
                        <ul class="mb-0">
                            <li>Satın aldığınız tokenlar hesabınıza otomatik olarak yüklenir.</li>
                            <li>Her film talebi için belirli miktarda token harcanır.</li>
                            <li>Token bakiyenizi profil sayfanızdan kontrol edebilirsiniz.</li>
                            <li>Tokenlar süresiz olarak hesabınızda kalır.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush
@endsection
