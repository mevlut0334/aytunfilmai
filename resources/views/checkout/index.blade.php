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
                    <!-- Test Modu Bilgilendirme -->
                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle-fill"></i> Test Modu Aktif</h6>
                        <p class="mb-2">Sandbox ortamındasınız. Gerçek para çekilmez.</p>
                        <small>
                            <strong>Test Kartları:</strong><br>
                            • Başarılı: 5528790000000008 | 12/30 | 123<br>
                            • Başarısız: 5406670000000009 | 12/30 | 123
                        </small>
                    </div>

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

                        <!-- Kredi Kartı Bilgileri -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Kredi Kartı Bilgileri</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="card_holder_name" class="form-label">Kart Üzerindeki İsim <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('card_holder_name') is-invalid @enderror"
                                           id="card_holder_name"
                                           name="card_holder_name"
                                           placeholder="Örn: AHMET YILMAZ"
                                           value="{{ old('card_holder_name') }}"
                                           required>
                                    @error('card_holder_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="card_number" class="form-label">Kart Numarası <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('card_number') is-invalid @enderror"
                                           id="card_number"
                                           name="card_number"
                                           placeholder="1234 5678 9012 3456"
                                           maxlength="19"
                                           value="{{ old('card_number') }}"
                                           required>
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Test: 5528790000000008 (Başarılı)</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="expire_month" class="form-label">Ay <span class="text-danger">*</span></label>
                                    <select class="form-select @error('expire_month') is-invalid @enderror"
                                            id="expire_month"
                                            name="expire_month"
                                            required>
                                        <option value="">Seçiniz</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                {{ old('expire_month') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('expire_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="expire_year" class="form-label">Yıl <span class="text-danger">*</span></label>
                                    <select class="form-select @error('expire_year') is-invalid @enderror"
                                            id="expire_year"
                                            name="expire_year"
                                            required>
                                        <option value="">Seçiniz</option>
                                        @for($i = date('y'); $i <= date('y') + 10; $i++)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                {{ old('expire_year') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                20{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('expire_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cvc" class="form-label">CVC <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('cvc') is-invalid @enderror"
                                           id="cvc"
                                           name="cvc"
                                           placeholder="123"
                                           maxlength="3"
                                           value="{{ old('cvc') }}"
                                           required>
                                    @error('cvc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

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
                        <button type="submit" class="btn btn-success btn-lg w-100" id="submit-btn">
                            <i class="bi bi-lock"></i> Güvenli Ödeme Yap
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
                        <strong>İyzico</strong> altyapısı ile güvenli ödeme<br>
                        256-bit SSL şifreleme
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Kart numarası formatla (boşluk ekle)
    document.getElementById('card_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Sadece rakam girişi
    document.getElementById('card_number').addEventListener('keypress', function(e) {
        if (!/\d/.test(e.key) && e.key !== 'Backspace') {
            e.preventDefault();
        }
    });

    document.getElementById('cvc').addEventListener('keypress', function(e) {
        if (!/\d/.test(e.key) && e.key !== 'Backspace') {
            e.preventDefault();
        }
    });

    // Form submit - boşlukları temizle ve buton devre dışı
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Kart numarasındaki boşlukları kaldır
        const cardInput = document.getElementById('card_number');
        cardInput.value = cardInput.value.replace(/\s/g, '');

        // Butonu devre dışı bırak
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>İşleniyor...';
    });
</script>
@endpush
@endsection
