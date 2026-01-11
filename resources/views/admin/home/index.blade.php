@extends('layouts.admin')

@section('title', 'Ana Sayfa Yönetimi')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="mb-4">
        <h1 class="mt-4">
            <i class="bi bi-house-door"></i> Ana Sayfa Yönetimi
        </h1>
        <p class="text-muted">Web sitesinin ana sayfa bölümlerini buradan yönetebilirsiniz.</p>
    </div>

    <!-- Yönetim Kartları -->
    <div class="row g-4">
        <!-- Slider Yönetimi -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-primary">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-images text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Slider Yönetimi</h5>
                    <p class="card-text text-muted">Ana sayfa carousel görselleri</p>
                    <a href="{{ route('admin.home.sliders') }}" class="btn btn-primary">
                        <i class="bi bi-gear"></i> Yönet
                    </a>
                </div>
            </div>
        </div>

        <!-- Kaydırmalı Görseller -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-success">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-arrow-left-right text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Kaydırmalı Görseller</h5>
                    <p class="card-text text-muted">Sonsuz kaydırma görselleri</p>
                    <a href="{{ route('admin.home.scrolling') }}" class="btn btn-success">
                        <i class="bi bi-gear"></i> Yönet
                    </a>
                </div>
            </div>
        </div>

        <!-- SSS Yönetimi -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-question-circle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">SSS Yönetimi</h5>
                    <p class="card-text text-muted">Sıkça sorulan sorular</p>
                    <a href="{{ route('admin.home.faqs') }}" class="btn btn-warning">
                        <i class="bi bi-gear"></i> Yönet
                    </a>
                </div>
            </div>
        </div>

        <!-- Site Ayarları -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm h-100 border-info">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-gear-fill text-info" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Site Ayarları</h5>
                    <p class="card-text text-muted">WhatsApp numarası vb.</p>
                    <a href="{{ route('admin.home.settings') }}" class="btn btn-info">
                        <i class="bi bi-gear"></i> Yönet
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bilgilendirme -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle-fill"></i> Bilgi</h6>
                <ul class="mb-0">
                    <li><strong>Slider:</strong> Ana sayfada 2 adet carousel görseli gösterir</li>
                    <li><strong>Kaydırmalı Görseller:</strong> Sonsuz kaydırma efekti ile görseller</li>
                    <li><strong>SSS:</strong> Accordion yapısında soru-cevap listesi</li>
                    <li><strong>Site Ayarları:</strong> Footer'daki WhatsApp numarası ve genel ayarlar</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
