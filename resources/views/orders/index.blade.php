@extends('layouts.app')

@section('title', 'Siparişlerim - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-bag-check"></i> Siparişlerim</h2>
        </div>
    </div>

    @if($orders->isEmpty())
        <!-- Boş Durum -->
        <div class="row">
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h3 class="mt-4">Henüz Siparişiniz Yok</h3>
                        <p class="text-muted mb-4">Token paketi satın alarak film üretmeye başlayabilirsiniz.</p>
                        <a href="{{ route('packages.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-bag-plus"></i> Token Paketlerine Git
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Sipariş Listesi -->
        <div class="row">
            <div class="col-12">
                @foreach($orders as $order)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Sol: Sipariş Bilgileri -->
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="mb-0 me-3">
                                            Sipariş #{{ $order->id }}
                                        </h5>
                                        @if($order->status === 'completed')
                                            <span class="badge bg-success">Tamamlandı</span>
                                        @elseif($order->status === 'pending')
                                            <span class="badge bg-warning text-dark">Beklemede</span>
                                        @else
                                            <span class="badge bg-danger">Başarısız</span>
                                        @endif
                                    </div>

                                    <!-- Tarih -->
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-calendar"></i>
                                        {{ $order->created_at->format('d.m.Y H:i') }}
                                    </p>

                                    <!-- Ürünler -->
                                    <div class="mb-2">
                                        @foreach($order->orderItems as $item)
                                            <span class="badge bg-light text-dark me-1">
                                                {{ $item->package->name }} x {{ $item->quantity }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Token -->
                                    <p class="mb-0">
                                        <i class="bi bi-coin text-warning"></i>
                                        <strong>{{ number_format((int) $order->totalTokens, 0) }} Token</strong>
                                    </p>
                                </div>

                                <!-- Sağ: Tutar ve Detay -->
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <!-- Tutar -->
                                    <h4 class="text-success mb-3">
                                        {{ number_format($order->final_amount, 2) }} ₺
                                    </h4>

                                    @if($order->discount_amount > 0)
                                        <p class="text-muted small mb-3">
                                            İndirim: -{{ number_format($order->discount_amount, 2) }} ₺
                                        </p>
                                    @endif

                                    <!-- Detay Butonu -->
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detay
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h3 class="mb-1">{{ $orders->where('status', 'completed')->count() }}</h3>
                        <p class="mb-0">Tamamlanan Sipariş</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h3 class="mb-1">{{ number_format($orders->where('status', 'completed')->sum('final_amount'), 2) }} ₺</h3>
                        <p class="mb-0">Toplam Harcama</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <h3 class="mb-1">
                            {{ number_format($orders->where('status', 'completed')->sum(function($order) {
                                return $order->orderItems->sum(function($item) {
                                    return $item->quantity * $item->package->token_amount;
                                });
                            }), 0) }}
                        </h3>
                        <p class="mb-0">Toplam Token</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
