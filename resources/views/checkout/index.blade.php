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
                    <h5 class="mb-0"><i class="bi bi-person"></i> Bilgileriniz</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf

                        <!-- Bilgi Kutusu -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle"></i>
                            <strong>Havale/EFT ile Ödeme:</strong> Siparişinizi oluşturduktan sonra Havale/EFT yapmanız gerekmektedir.Banka bilgilerimize ana sayfadan ulaşabilir veya whatsapp destek hattımızdan talep edebilirsiniz. Havale/EFT yaptıktan sonra dekontunuzu WhatsApp'tan bize gönderebilirsiniz.
                        </div>

                        <!-- Bilgiler -->
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', auth()->user()->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', auth()->user()->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           placeholder="0555 123 45 67"
                                           value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Örn: 0555 123 45 67</small>
                                </div>
                            </div>
                        </div>

                        <!-- Sözleşmeler -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                       type="checkbox"
                                       id="terms"
                                       name="terms"
                                       required>
                                <label class="form-check-label" for="terms">
                                    <a href="{{ route('legal.terms') }}" target="_blank">Kullanım Koşulları</a>'nı ve
                                    <a href="{{ route('legal.kvkk') }}" target="_blank">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ödeme Butonu -->
                        <button type="submit" class="btn btn-success btn-lg w-100" id="submit-btn">
                            <i class="bi bi-check-circle"></i> Siparişi Oluştur
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
                        <strong>Güvenli Ödeme</strong><br>
                        Havale/EFT ile ödeme
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Telefon numarası formatla
    document.getElementById('phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);

        // 0555 123 45 67 formatında göster
        if (value.length > 7) {
            e.target.value = value.slice(0, 4) + ' ' + value.slice(4, 7) + ' ' + value.slice(7, 9) + ' ' + value.slice(9);
        } else if (value.length > 4) {
            e.target.value = value.slice(0, 4) + ' ' + value.slice(4);
        } else {
            e.target.value = value;
        }
    });

    // Sadece rakam girişi
    document.getElementById('phone').addEventListener('keypress', function(e) {
        if (!/\d/.test(e.key) && e.key !== 'Backspace') {
            e.preventDefault();
        }
    });

    // Form submit - telefon boşluklarını temizle ve buton devre dışı
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Telefon numarasındaki boşlukları kaldır
        const phoneInput = document.getElementById('phone');
        phoneInput.value = phoneInput.value.replace(/\s/g, '');

        // Butonu devre dışı bırak
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>İşleniyor...';
    });
</script>
@endpush
@endsection
