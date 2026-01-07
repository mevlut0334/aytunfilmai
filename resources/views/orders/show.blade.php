@extends('layouts.app')

@section('title', 'Sipariş Detayı - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık ve Geri Dön -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Siparişlerime Dön
            </a>
            <h2><i class="bi bi-receipt"></i> Sipariş Detayı #{{ $order->id }}</h2>
        </div>
    </div>

    <div class="row">
        <!-- Sol: Sipariş Bilgileri -->
        <div class="col-lg-8 mb-4">
            <!-- Durum Kartı -->
            <div class="card shadow-sm mb-3">
                <div class="card-header
                    @if($order->status === 'completed') bg-success
                    @elseif($order->status === 'pending') bg-warning
                    @else bg-danger
                    @endif text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Sipariş Durumu
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Durum</small>
                            <h5>
                                @if($order->status === 'completed')
                                    <span class="badge bg-success">Tamamlandı</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Beklemede</span>
                                @else
                                    <span class="badge bg-danger">Başarısız</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted">Sipariş Tarihi</small>
                            <h5>{{ $order->created_at->format('d.m.Y H:i') }}</h5>
                        </div>
                        @if($order->payment_date)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Ödeme Tarihi</small>
                                <h5>{{ $order->payment_date->format('d.m.Y H:i') }}</h5>
                            </div>
                        @endif
                        @if($order->transaction_id)
                            <div class="col-md-6 mb-3">
                                <small class="text-muted">İşlem ID</small>
                                <h5><code>{{ $order->transaction_id }}</code></h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sipariş Kalemleri -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bag"></i> Sipariş Kalemleri</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paket</th>
                                    <th class="text-center">Birim Fiyat</th>
                                    <th class="text-center">Miktar</th>
                                    <th class="text-center">Token</th>
                                    <th class="text-end">Ara Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->package->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ number_format($item->package->token_amount, 0) }} Token/Adet
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($item->unit_price, 2) }} ₺
                                        </td>
                                        <td class="text-center">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">
                                                {{ number_format($item->quantity * $item->package->token_amount, 0) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <strong>{{ number_format($item->subtotal, 2) }} ₺</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ: Özet ve Fatura -->
        <div class="col-lg-4">
            <!-- Tutar Özeti -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-calculator"></i> Tutar Özeti</h6>
                </div>
                <div class="card-body">
                    <!-- Ara Toplam -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ara Toplam:</span>
                        <strong>{{ number_format($order->total_amount, 2) }} ₺</strong>
                    </div>

                    <!-- İndirim -->
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>
                                İndirim
                                @if($order->coupon)
                                    <br><small>({{ $order->coupon->code }})</small>
                                @endif
                            </span>
                            <strong>- {{ number_format($order->discount_amount, 2) }} ₺</strong>
                        </div>
                    @endif

                    <hr>

                    <!-- Toplam -->
                    <div class="d-flex justify-content-between mb-0">
                        <h5 class="mb-0">Toplam:</h5>
                        <h5 class="mb-0 text-success">{{ number_format($order->final_amount, 2) }} ₺</h5>
                    </div>
                </div>
            </div>

            <!-- Token Bilgisi -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bi bi-coin"></i> Token Bilgisi</h6>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-coin display-3 text-warning"></i>
                    <h2 class="mt-3 mb-0">{{ number_format((int) $order->totalTokens, 0) }}</h2>
                    <p class="text-muted mb-0">Yüklenen Token</p>
                </div>
            </div>

            <!-- Müşteri Bilgileri -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Müşteri Bilgileri</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Ad Soyad:</strong></p>
                    <p class="mb-2">{{ $order->user->name }}</p>

                    <p class="mb-1"><strong>E-posta:</strong></p>
                    <p class="mb-2">{{ $order->user->email }}</p>

                    <p class="mb-1"><strong>Telefon:</strong></p>
                    <p class="mb-0">{{ $order->user->phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
