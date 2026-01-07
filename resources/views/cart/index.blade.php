@extends('layouts.app')

@section('title', 'Sepetim - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-cart"></i> Sepetim</h2>
        </div>
    </div>

    @if($cartItems->isEmpty())
        <!-- Boş Sepet -->
        <div class="row">
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-cart-x display-1 text-muted"></i>
                        <h3 class="mt-4">Sepetiniz Boş</h3>
                        <p class="text-muted mb-4">Henüz sepetinize ürün eklemediniz.</p>
                        <a href="{{ route('packages.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-bag-plus"></i> Token Paketlerine Git
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <!-- Sol: Sepet Öğeleri -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-list"></i> Sepet Öğeleri ({{ $cartItems->count() }})</h5>
                        <form action="{{ route('cart.clear') }}" method="POST"
                              onsubmit="return confirm('Sepeti temizlemek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="bi bi-trash"></i> Sepeti Temizle
                            </button>
                        </form>
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
                                        <th class="text-center">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->package->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ number_format($item->package->token_amount, 0) }} Token/Adet
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($item->price, 2) }} ₺
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                                        <select name="quantity"
                                                                class="form-select form-select-sm"
                                                                onchange="this.form.submit()">
                                                            @for($i = 1; $i <= 99; $i++)
                                                                <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">
                                                    {{ number_format($item->quantity * $item->package->token_amount, 0) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <strong>{{ number_format($item->subtotal, 2) }} ₺</strong>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Sepetten Çıkar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Alışverişe Devam Et -->
                <div class="mt-3">
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Alışverişe Devam Et
                    </a>
                </div>
            </div>

            <!-- Sağ: Sipariş Özeti -->
            <div class="col-lg-4">
                <!-- Kupon Kartı -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-tag"></i> İndirim Kuponu</h6>
                    </div>
                    <div class="card-body">
                        @if($couponCode)
                            <!-- Kupon Uygulandı -->
                            <div class="alert alert-success mb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $couponCode }}</strong>
                                    <br>
                                    <small>Kupon uygulandı!</small>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Kupon Uygula -->
                            <form action="{{ route('cart.coupon.apply') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text"
                                           name="coupon_code"
                                           class="form-control @error('coupon_code') is-invalid @enderror"
                                           placeholder="Kupon kodu girin"
                                           style="text-transform: uppercase;">
                                    <button type="submit" class="btn btn-info text-white">
                                        Uygula
                                    </button>
                                </div>
                                @error('coupon_code')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Sipariş Özeti Kartı -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-receipt"></i> Sipariş Özeti</h6>
                    </div>
                    <div class="card-body">
                        <!-- Ara Toplam -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ara Toplam:</span>
                            <strong>{{ number_format($cartSummary['subtotal'], 2) }} ₺</strong>
                        </div>

                        <!-- İndirim -->
                        @if($cartSummary['discount'] > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>İndirim:</span>
                                <strong>- {{ number_format($cartSummary['discount'], 2) }} ₺</strong>
                            </div>
                        @endif

                        <hr>

                        <!-- Toplam -->
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Toplam:</h5>
                            <h5 class="mb-0 text-success">{{ number_format($cartSummary['total'], 2) }} ₺</h5>
                        </div>

                        <!-- Toplam Token -->
                        <div class="alert alert-warning mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-coin"></i> Toplam Token:</span>
                                <strong class="fs-5">{{ number_format($cartSummary['total_tokens'], 0) }}</strong>
                            </div>
                        </div>

                        <!-- Ödeme Butonu -->
                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-credit-card"></i> Ödemeye Geç
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
