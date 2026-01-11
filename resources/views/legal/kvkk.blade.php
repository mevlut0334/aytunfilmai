@extends('layouts.app')

@section('title', 'KVKK Aydınlatma Metni - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">KVKK Aydınlatma Metni</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Son Güncelleme: {{ date('d.m.Y') }}</p>

                    <h4>1. Veri Sorumlusu</h4>
                    <p><strong>Aytun Film AI</strong> olarak 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca veri sorumlusuyuz.</p>

                    <h4>2. İşlenen Kişisel Veriler</h4>
                    <ul>
                        <li><strong>Kimlik Bilgileri:</strong> Ad, soyad</li>
                        <li><strong>İletişim Bilgileri:</strong> E-posta, telefon</li>
                        <li><strong>Müşteri İşlem Bilgileri:</strong> Sipariş geçmişi, token kullanımı</li>
                        <li><strong>İşlem Güvenliği Bilgileri:</strong> IP adresi, cihaz bilgisi</li>
                        <li><strong>Görsel Veriler:</strong> Film talebi için yüklenen görseller</li>
                    </ul>

                    <h4>3. Kişisel Verilerin İşlenme Amaçları</h4>
                    <ul>
                        <li>Hizmet sağlama ve geliştirme</li>
                        <li>Müşteri ilişkileri yönetimi</li>
                        <li>Ödeme işlemlerinin gerçekleştirilmesi</li>
                        <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                        <li>Güvenlik ve dolandırıcılık önleme</li>
                    </ul>

                    <h4>4. Kişisel Verilerin Aktarımı</h4>
                    <p>Kişisel verileriniz, hizmet sağlayıcılar (ödeme sistemleri, bulut hizmetleri) ve yasal mercilere aktarılabilir.</p>

                    <h4>5. Kişisel Verilerin Saklanma Süresi</h4>
                    <p>Verileriniz, hizmet sunumu için gerekli süre ve yasal saklama yükümlülüğü boyunca saklanır.</p>

                    <h4>6. Haklarınız (KVKK Madde 11)</h4>
                    <ul>
                        <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                        <li>İşlenmişse bilgi talep etme</li>
                        <li>İşlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                        <li>Yurt içi/yurt dışı aktarılan üçüncü kişileri bilme</li>
                        <li>Eksik/yanlış işlenmişse düzeltme talep etme</li>
                        <li>KVKK'da öngörülen şartlar çerçevesinde silinmesini/yok edilmesini isteme</li>
                        <li>Düzeltme/silme işlemlerinin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                        <li>Otomatik sistemlerle analiz edilmesi sonucu aleyhinize sonuç doğmasına itiraz etme</li>
                        <li>Kanuna aykırı işleme nedeniyle zararınızın tazmini</li>
                    </ul>

                    <h4>7. Başvuru Yöntemi</h4>
                    <p>KVKK haklarınızı kullanmak için: <a href="mailto:kvkk@aytunfilmai.com">kvkk@aytunfilmai.com</a></p>

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
