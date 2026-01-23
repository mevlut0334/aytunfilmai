@extends('layouts.app')

@section('title', 'Kullanım Koşulları - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Kullanım Koşulları</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Son Güncelleme: {{ date('d.m.Y') }}</p>

                    <h4>1. Genel Koşullar</h4>
                    <p>Aytun Film AI platformunu kullanarak aşağıdaki kullanım koşullarını kabul etmiş sayılırsınız.</p>

                    <h4>2. Hizmet Kullanımı</h4>
                    <p>Platformumuz, yapay zeka destekli film üretimi hizmeti sunmaktadır. Kullanıcılar, satın aldıkları token'lar ile film talepleri oluşturabilir.</p>

                    <h4>3. Token Sistemi</h4>
                    <ul>
                        <li>Token'lar satın alındıktan sonra iade edilemez</li>
                        <li>Token'ların geçerlilik süresi yoktur</li>
                        <li>Token'lar başka kullanıcılara devredilemez</li>
                    </ul>

                    <h4>4. Film Üretimi</h4>
                    <ul>
                        <li>Üretilen filmler yapay zeka tarafından oluşturulur</li>
                        <li>Film kalitesi ve süresi paket içeriğine bağlıdır</li>
                        <li>Talepler 24-48 saat içinde işleme alınır</li>
                    </ul>

                    <h4>5. Kullanıcı Sorumlulukları</h4>
                    <ul>
                        <li>Yüklenen içeriklerin telif haklarına uygun olmalıdır</li>
                        <li>Yasal olmayan, zararlı veya müstehcen içerik yüklenemez</li>
                        <li>Hesap bilgilerinin güvenliği kullanıcının sorumluluğundadır</li>
                    </ul>

                    <h4>6. Hizmet Değişiklikleri</h4>
                    <p>Platform, hizmet koşullarını önceden bildirmeksizin değiştirme hakkını saklı tutar.</p>

                    <h4>7. Sorumluluk Reddi</h4>
                    <p>Yapay zeka tarafından üretilen içeriklerden kaynaklanan sorunlardan platform sorumlu tutulamaz.</p>

                    <h4>8. İletişim</h4>
                    <p>Sorularınız için: <a href="mailto:mevluttuncer0334@gmail.com">mevluttuncer0334@gmail.com</a></p>

                    <div class="mt-4">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Kayıt Sayfasına Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
