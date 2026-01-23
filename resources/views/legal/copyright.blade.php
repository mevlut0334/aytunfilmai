@extends('layouts.app')

@section('title', 'Telif Hakları Beyanı - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Telif Hakları Beyanı</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Son Güncelleme: {{ date('d.m.Y') }}</p>

                    <div class="alert alert-danger">
                        <h5><i class="bi bi-exclamation-triangle-fill"></i> ÖNEMLİ UYARI</h5>
                        <p class="mb-0">Telif haklarını ihlal eden içerik yükleyen kullanıcılar yasal sorumluluğu kabul eder.</p>
                    </div>

                    <h4>1. Telif Hakları Sorumluluğu</h4>
                    <p>Kullanıcılar, platforma yükledikleri tüm içeriklerin (görsel, metin, ses, vb.) telif haklarına sahip olduklarını veya kullanım izni aldıklarını beyan eder.</p>

                    <h4>2. Yasaklanan İçerikler</h4>
                    <ul>
                        <li>Başkalarına ait telif hakkıyla korunan görsel, müzik veya metinler</li>
                        <li>İzinsiz kullanılan ünlü kişilerin fotoğrafları</li>
                        <li>Marka logoları ve tescilli tasarımlar</li>
                        <li>Lisanssız film, dizi veya oyun karakterleri</li>
                    </ul>

                    <h4>3. Platform Sorumluluğu</h4>
                    <p>Aytun Film AI, kullanıcıların yüklediği içeriklerin telif haklarından sorumlu değildir. İhlal durumunda içerik derhal kaldırılır ve kullanıcı hesabı askıya alınır.</p>

                    <h4>4. Üretilen İçerikler</h4>
                    <p>Yapay zeka ile üretilen filmlerin telif hakları kullanıcıya aittir. Ancak kullanıcı, platform tarafından pazarlama amaçlı kullanılmasına izin verir.</p>

                    <h4>5. İhlal Bildirimi</h4>
                    <p>Telif hakkı ihlali bildirimleri için: <a href="mailto:mevluttuncer0334@gmail.com">mevluttuncer0334@gmail.com</a></p>

                    <h4>6. Yasal Süreç</h4>
                    <p>Telif hakkı ihlali yapan kullanıcılar hakkında yasal işlem başlatılabilir ve tazminat talep edilebilir.</p>

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
