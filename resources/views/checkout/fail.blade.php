@extends('layouts.app')

@section('title', 'Ödeme Başarısız - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Hata Kartı -->
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <!-- Hata İkonu -->
                    <div class="mb-4">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 6rem;"></i>
                    </div>

                    <!-- Başlık -->
                    <h1 class="text-danger mb-3">Ödeme Başarısız!</h1>
                    <p class="lead text-muted mb-4">
                        Üzgünüz, ödeme işleminiz tamamlanamadı.
                    </p>

                    <!-- Hata Açıklaması -->
                    <div class="alert alert-danger mb-4">
                        <h5><i class="bi bi-exclamation-triangle-fill"></i> Olası Nedenler</h5>
                        <ul class="text-start mb-0">
                            <li>Kredi kartı bilgileriniz hatalı olabilir</li>
                            <li>Kartınızda yeterli bakiye bulunmayabilir</li>
                            <li>Kartınız online işlemlere kapalı olabilir</li>
                            <li>Banka tarafından işlem reddedilmiş olabilir</li>
                            <li>Teknik bir sorun yaşanmış olabilir</li>
                        </ul>
                    </div>

                    <!-- Bilgilendirme -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="bi bi-info-circle"></i> Ne Yapmalıyım?</h6>
                            <div class="text-start">
                                <ol class="mb-0">
                                    <li>Kart bilgilerinizi kontrol edin</li>
                                    <li>Bankanızla iletişime geçin</li>
                                    <li>Farklı bir kart ile tekrar deneyin</li>
                                    <li>Sorun devam ederse destek ekibimizle iletişime geçin</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Aksiyon Butonları -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('cart.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-left"></i> Sepete Dön
                        </a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-arrow-clockwise"></i> Tekrar Dene
                        </a>
                    </div>

                    <!-- Destek -->
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted mb-2">
                            <i class="bi bi-headset"></i> Yardıma mı ihtiyacınız var?
                        </p>
                        <p class="text-muted mb-0">
                            Destek ekibimize <strong>destek@aytunfilm.ai</strong> adresinden ulaşabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
