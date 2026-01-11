@extends('layouts.app')

@section('title', 'Kişisel Verilerin İşlenmesi Onayı - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Kişisel Verilerin İşlenmesi Onayı</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Son Güncelleme: {{ date('d.m.Y') }}</p>

                    <h4>1. Onay Kapsamı</h4>
                    <p>Bu onay metni ile, KVKK kapsamında kişisel verilerinizin işlenmesine açık rıza veriyorsunuz.</p>

                    <h4>2. İşlenecek Veriler</h4>
                    <ul>
                        <li>Ad, soyad, e-posta, telefon numarası</li>
                        <li>Ödeme bilgileri (kart bilgileri şifreli saklanır)</li>
                        <li>Platform kullanım geçmişi</li>
                        <li>Yüklenen görsel ve video dosyaları</li>
                        <li>IP adresi ve çerez bilgileri</li>
                    </ul>

                    <h4>3. İşleme Amaçları</h4>
                    <ul>
                        <li><strong>Hizmet Sağlama:</strong> Film üretimi ve token yönetimi</li>
                        <li><strong>İletişim:</strong> Bildirimler, destek, pazarlama</li>
                        <li><strong>Analiz:</strong> Hizmet kalitesinin iyileştirilmesi</li>
                        <li><strong>Güvenlik:</strong> Dolandırıcılık önleme ve hesap güvenliği</li>
                        <li><strong>Yasal Yükümlülük:</strong> Fatura, vergi, muhasebe kayıtları</li>
                    </ul>

                    <h4>4. Pazarlama İletişimi</h4>
                    <p>Kampanya, yeni özellik ve promosyon bilgileri için e-posta ve SMS gönderilmesine izin veriyorsunuz. Bu izni istediğiniz zaman geri çekebilirsiniz.</p>

                    <h4>5. Veri Paylaşımı</h4>
                    <p>Verileriniz aşağıdaki durumlarda üçüncü kişilerle paylaşılabilir:</p>
                    <ul>
                        <li>Ödeme sağlayıcıları (İyzico, vb.)</li>
                        <li>Bulut hizmet sağlayıcıları (AWS, Google Cloud)</li>
                        <li>Yasal mercilerin talebi üzerine</li>
                        <li>İş ortakları (analiz, reklam)</li>
                    </ul>

                    <h4>6. Çerezler (Cookies)</h4>
                    <p>Platformumuz, kullanıcı deneyimini iyileştirmek için çerezler kullanır. Tarayıcı ayarlarından çerezleri reddedebilirsiniz.</p>

                    <h4>7. Onayın Geri Çekilmesi</h4>
                    <p>Bu onayı istediğiniz zaman <a href="mailto:kvkk@aytunfilmai.com">kvkk@aytunfilmai.com</a> adresine e-posta göndererek geri çekebilirsiniz. Onay geri çekildiğinde hesabınız kapatılacaktır.</p>

                    <h4>8. Veri Güvenliği</h4>
                    <p>Kişisel verileriniz SSL şifreleme, güvenlik duvarı ve erişim kontrolü ile korunmaktadır.</p>

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
