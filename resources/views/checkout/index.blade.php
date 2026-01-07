@extends('layouts.app')

@section('title', 'Ödeme - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-credit-card"></i> Ödeme</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">Paketler</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Sepet</a></li>
                    <li class="breadcrumb-item active">Ödeme</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Sol: Ödeme Formu -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Ödeme Bilgileri</h5>
                </div>
                <div class="card-body">
                    <!-- TODO: İyzico Entegrasyonu -->
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle-fill"></i> Bilgilendirme</h5>
                        <p class="mb-0">
                            Ödeme altyapısı şu anda test modundadır.
                            "Ödemeyi Tamamla" butonuna tıkladığınızda, ödeme otomatik olarak onaylanacak ve tokenlarınız hesabınıza yüklenecektir.
                        </p>
                    </div>

                    <!-- TODO: İyzico entegrasyonu yapılınca bu form aktif olacak -->
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf

                        <!-- Fatura Bilgileri -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Fatura Bilgileri</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Ad Soyad</label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           value="{{ auth()->user()->name }}"
                                           readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-posta</label>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           value="{{ auth()->user()->email }}"
                                           readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefon</label>
                                    <input type="text"
                                           class="form-control"
                                           id="phone"
                                           value="{{ auth()->user()->phone }}"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Kredi Kartı Bilgileri (İyzico için hazır) -->
                        <!--
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Kredi Kartı Bilgileri</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="card_holder_name" class="form-label">Kart Üzerindeki İsim</label>
                                    <input type="text"
                                           class="form-control"
                                           id="card_holder_name"
                                           name="card_holder_name"
                                           required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="card_number" class="form-label">Kart Numarası</label>
                                    <input type="text"
                                           class="form-control"
                                           id="card_number"
                                           name="card_number"
                                           maxlength="16"
                                           required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="expire_month" class="form-label">Ay</label>
                                    <select class="form-select" id="expire_month" name="expire_month" required>
                                        <option value="">Ay</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="expire_year" class="form-label">Yıl</label>
                                    <select class="form-select" id="expire_year" name="expire_year" required>
                                        <option value="">Yıl</option>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cvc" class="form-label">CVV</label>
                                    <input type="text"
                                           class="form-control"
                                           id="cvc"
                                           name="cvc"
                                           maxlength="3"
                                           required>
                                </div>
                            </div>
                        </div>
                        -->

                        <!-- Sözleşmeler -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="terms"
                                       required>
                                <label class="form-check-label" for="terms">
                                    <a href="#" target="_blank">Kullanım Koşulları</a>'nı ve
                                    <a href="#" target="_blank">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.
                                </label>
                            </div>
                        </div>

                        <!-- Ödeme Butonu -->
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-lock"></i> Ödemeyi Tamamla
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ: Sipariş Özeti -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-receipt"></i> Sipariş Özeti</h6>
                </div>
                <div class="card-body">
                    <!-- Sepet Öğeleri -->
                    <div class="mb-3">
                        <h6 class="border-bottom pb-2 mb-3">Ürünler</h6>
                        @foreach($cartSummary['items'] as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong>{{ $item->package->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $item->quantity }} Adet</small>
                                </div>
                                <span>{{ number_format($item->subtotal, 2) }} ₺</span>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <!-- Ara Toplam -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ara Toplam:</span>
                        <strong>{{ number_format($cartSummary['subtotal'], 2) }} ₺</strong>
                    </div>

                    <!-- İndirim -->
                    @if($cartSummary['discount'] > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>
                                İndirim
                                @if($couponCode)
                                    <br><small>({{ $couponCode }})</small>
                                @endif
                            </span>
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
                    <div class="alert alert-warning mb-0">
                        <div class="text-center">
                            <i class="bi bi-coin display-6 text-warning"></i>
                            <h5 class="mt-2 mb-0">{{ number_format($cartSummary['total_tokens'], 0) }} Token</h5>
                            <small>Hesabınıza yüklenecek</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Güvenlik Bilgisi -->
            <div class="card bg-light mt-3">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check text-success display-5"></i>
                    <p class="mb-0 mt-2 small">
                        Ödeme işlemleriniz 256-bit SSL ile güvence altındadır.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
