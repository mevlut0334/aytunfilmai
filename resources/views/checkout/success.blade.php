@extends('layouts.app')

@section('title', 'Ödeme Başarılı - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Başarı Kartı -->
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Başarı İkonu -->
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 6rem;"></i>
                    </div>

                    <!-- Başlık -->
                    <h1 class="text-success mb-3">Ödeme Başarılı!</h1>
                    <p class="lead text-muted mb-4">
                        Siparişiniz başarıyla tamamlandı. Tokenlarınız hesabınıza yüklendi.
                    </p>

                    <!-- Sipariş Bilgileri -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Sipariş Numarası</small>
                                    <h5 class="mb-0">#{{ $order->id }}</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Tarih</small>
                                    <h5 class="mb-0">{{ $order->created_at->format('d.m.Y H:i') }}</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Toplam Tutar</small>
                                    <h5 class="mb-0 text-success">{{ number_format($order->final_amount, 2) }} ₺</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted">Yüklenen Token</small>
                                    <h5 class="mb-0 text-warning">
                                        <i class="bi bi-coin"></i> {{ number_format($order->totalTokens, 0) }}
                                    </h5>
                                </div>
                            </div>

                            <!-- Sipariş Kalemleri -->
                            <hr>
                            <small class="text-muted">Satın Alınan Paketler</small>
                            <div class="mt-2">
                                @foreach($order->orderItems as $item)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>{{ $item->package->name }} x {{ $item->quantity }}</span>
                                        <strong>{{ number_format($item->subtotal, 2) }} ₺</strong>
                                    </div>
                                @endforeach
                            </div>

                            @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between text-success mt-2">
                                    <span>İndirim</span>
                                    <strong>- {{ number_format($order->discount_amount, 2) }} ₺</strong>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Mevcut Token Bakiyesi -->
                    <div class="alert alert-info mb-4">
                        <h5><i class="bi bi-info-circle-fill"></i> Güncel Token Bakiyeniz</h5>
                        <h2 class="mb-0 text-primary">
                            <i class="bi bi-coin"></i> {{ number_format((int) $order->totalTokens, 0) }}
                        </h2>
                    </div>

                    <!-- Aksiyon Butonları -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-receipt"></i> Sipariş Detayı
                        </a>
                        <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-person-circle"></i> Profilime Git
                        </a>
                    </div>

                    <!-- Teşekkür Mesajı -->
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted mb-0">
                            <i class="bi bi-heart-fill text-danger"></i> Aytun Film AI'ı tercih ettiğiniz için teşekkür ederiz!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
