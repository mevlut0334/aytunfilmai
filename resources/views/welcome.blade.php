<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yapay Zeka Film Yapımı</title>
    <meta name="description" content="Yapay zeka ile profesyonel film ve video içerikleri üretin. Token satın alın, senaryonuzu yazın ve dakikalar içinde filminizi alın. Hızlı, kolay ve uygun fiyatlı AI film üretimi.">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            /* Ana Renkler */
            --primary: #00D9FF;
            --primary-dark: #0099CC;
            --secondary: #FF006E;
            --accent: #8338EC;
            /* Arka Plan */
            --bg-dark: #000000;
            --bg-medium: #0A0A0A;
            /* Bootstrap Accordion Override */
            --bs-accordion-color: #FFFFFF;
            --bs-accordion-active-color: #FFFFFF;
            --bs-accordion-btn-color: #FFFFFF;
            --bs-accordion-btn-active-color: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-dark);
            color: white;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 217, 255, 0.2);
            padding: 1rem 0;
        }

        .navbar-custom .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }

        .navbar-custom .nav-link {
            color: white;
            margin: 0 1rem;
            transition: all 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: var(--primary);
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }

        .btn-neon {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            transition: all 0.3s;
        }

        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 217, 255, 0.6);
            color: white;
        }

        /* Slider Section */
        .slider-section {
            margin-top: 76px;
            width: 100%;
            overflow: hidden;
        }

        .carousel {
            width: 100%;
        }

        .carousel-inner {
            width: 100%;
        }

        .carousel-item {
            width: 100%;
            height: 600px;
            position: relative;
        }

        .carousel-item img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            filter: brightness(0.8);
            display: block;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            padding: 1rem 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(0, 217, 255, 0.3);
            max-width: 600px;
            margin: 0 auto;
            bottom: 3rem;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            z-index: 5; /* Kontrol butonlarının altında */
        }

        .carousel-caption h2 {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .carousel-caption .btn {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            z-index: 10; /* Caption'ın üstünde */
        }

        .carousel-indicators {
            display: none !important; /* Çizgileri tamamen kaldır */
        }

        /* How It Works Section */
        .how-it-works {
            padding: 5rem 0;
            background: var(--bg-medium);
        }

        .how-it-works h2 {
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: none;
        }

        .step-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            height: 100%;
        }

        .step-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
        }

        .step-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary);
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }

        .step-card h4 {
            color: white;
            font-weight: bold;
        }

        .step-card p {
            color: white;
            font-size: 1rem;
        }

        /* Scrolling Images */
        .scrolling-section {
            padding: 5rem 0;
            background: var(--bg-dark);
            overflow: hidden;
        }

        .scrolling-section h2 {
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .scroll-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            cursor: grab;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--secondary) rgba(255, 255, 255, 0.1);
        }

        .scroll-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .scroll-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .scroll-wrapper::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }

        .scroll-wrapper:active {
            cursor: grabbing;
        }

        .scroll-container {
            display: flex;
            gap: 2rem;
            animation: scroll 30s linear infinite;
            width: fit-content;
        }

        .scroll-wrapper:hover .scroll-container {
            animation-play-state: paused;
        }

        .scroll-item {
            flex: 0 0 300px;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
        }

        .scroll-item:hover {
            transform: scale(1.05);
            border-color: var(--secondary);
            box-shadow: 0 10px 30px rgba(255, 0, 110, 0.5);
        }

        .scroll-item img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .scroll-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            padding: 1.5rem;
            color: white;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        /* FAQ Section */
        .faq-section {
            padding: 5rem 0;
            background: var(--bg-medium);
        }

        .faq-section h2 {
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 3rem;
            background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(131, 56, 236, 0.2);
            margin-bottom: 1rem;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }

        .faq-item:hover {
            border-color: var(--accent);
            box-shadow: 0 5px 15px rgba(131, 56, 236, 0.2);
        }

        .faq-question {
            padding: 1.25rem 1.5rem;
            background: rgba(131, 56, 236, 0.1);
            color: #FFFFFF;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-question i {
            transition: transform 0.3s;
            color: #FFFFFF;
        }

        .faq-item.active .faq-question {
            background: rgba(131, 56, 236, 0.3);
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0 1.5rem;
            background: rgba(0, 0, 0, 0.3);
            color: rgba(255, 255, 255, 0.9);
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 1.25rem 1.5rem;
        }

        /* Footer */
        .footer {
            background: var(--bg-dark);
            border-top: 1px solid rgba(0, 217, 255, 0.2);
            padding: 3rem 0 1rem;
        }

        .footer-logo {
            max-height: 80px;
            max-width: 300px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 10px;
        }

        /* Sticky Footer Bar */
        .sticky-footer-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
            border-top: 2px solid var(--primary);
            padding: 1rem 0;
            z-index: 1000;
            box-shadow: 0 -5px 20px rgba(0, 217, 255, 0.3);
        }

        .sticky-footer-bar .btn {
            margin: 0 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-create {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: white;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 0, 110, 0.5);
            color: white;
        }

        .btn-token {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
        }

        .btn-token:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.5);
            color: white;
        }

        .btn-whatsapp {
            background: #25D366;
            border: none;
            color: white;
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(37, 211, 102, 0.5);
            background: #20BA5A;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            /* Slider - Mobil */
            .slider-section {
                margin-top: 56px; /* Navbar daha küçük mobilde */
            }

            .carousel-item {
                height: 300px;
                background: #000; /* Boşluklar siyah olsun */
            }

            .carousel-item img {
                width: 100% !important;
                height: 100% !important;
                object-fit: contain !important; /* Tüm görsel görünsün */
            }

            .carousel-caption {
                padding: 0.5rem 0.75rem;
                border-radius: 8px;
                bottom: 0.5rem;
                max-width: 85%;
            }

            .carousel-caption h2 {
                font-size: 0.85rem;
                margin-bottom: 0.35rem;
            }

            .carousel-caption .btn {
                padding: 0.3rem 0.75rem;
                font-size: 0.75rem;
            }

            .how-it-works h2,
            .scrolling-section h2,
            .faq-section h2 {
                font-size: 2rem;
            }

            /* How It Works - Yarı Alan */
            .how-it-works {
                padding: 2.5rem 0;
            }

            .step-card {
                padding: 1rem;
            }

            .step-number {
                font-size: 2rem;
            }

            .step-card h4 {
                font-size: 1rem;
            }

            .step-card p {
                font-size: 0.85rem;
            }

            /* Scrolling Images - Yarı Alan */
            .scrolling-section {
                padding: 2.5rem 0;
            }

            .scroll-item {
                flex: 0 0 200px;
            }

            .scroll-item img {
                height: 250px;
            }

            .scroll-overlay {
                padding: 0.75rem;
            }

            .scroll-overlay h5 {
                font-size: 0.9rem;
            }

            /* FAQ */
            .faq-section {
                padding: 2.5rem 0;
            }

            /* Footer - Mobil */
            .footer {
                padding: 2rem 0 1rem;
            }

            .footer-logo {
                max-height: 40px;
                margin-bottom: 1rem;
            }

            .footer-links a {
                font-size: 0.85rem;
                margin-bottom: 0.5rem;
            }

            /* Sticky Footer - Yan Yana Butonlar */
            .sticky-footer-bar .btn {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
                margin: 0.25rem;
                white-space: nowrap;
            }

            .sticky-footer-bar .btn i {
                font-size: 0.9rem;
            }

            .sticky-footer-bar .d-flex {
                gap: 0.25rem;
            }
        }

        /* Ekstra küçük ekranlar için */
        @media (max-width: 576px) {
            .sticky-footer-bar {
                padding: 0.75rem 0;
            }

            .sticky-footer-bar .btn {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
                margin: 0.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-film"></i> Aytun Film AI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> Paketler
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('requests.index') }}">
                                <i class="bi bi-film"></i> Taleplerim
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.profile') }}">
                                <i class="bi bi-person-circle"></i> Profilim
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="text-decoration: none;">
                                    <i class="bi bi-box-arrow-right"></i> Çıkış
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Giriş
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-neon" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Kayıt Ol
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Slider Section -->
    <section class="slider-section">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @php
                    $sliders = \App\Models\Slider::active()->ordered()->get();
                @endphp
                @foreach($sliders as $index => $slider)
                    <button type="button"
                            data-bs-target="#mainCarousel"
                            data-bs-slide-to="{{ $index }}"
                            class="{{ $index === 0 ? 'active' : '' }}">
                    </button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $slider->image) }}"
                             class="d-block w-100"
                             alt="{{ $slider->title }}">
                        @if($slider->title)
                            <div class="carousel-caption">
                                <h2>{{ $slider->title }}</h2>
                                @if($slider->link)
                                    <a href="{{ $slider->link }}" class="btn btn-neon mt-3">
                                        Daha Fazla <i class="bi bi-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- How It Works Section (Static) -->
    <section class="how-it-works">
        <div class="container">
            <h2>Nasıl Çalışır?</h2>
            <div class="row g-4">
                <!-- Step 1 -->
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4 class="mt-3">Kayıt Ol</h4>
                        <p>Hızlı ve kolay kayıt işlemi ile başla</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4 class="mt-3">Token Satın Al</h4>
                        <p>İhtiyacına göre token paketi seç</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4 class="mt-3">Film Talebi Oluştur</h4>
                        <p>Yapay zeka ile senaryonu yaz</p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4 class="mt-3">Filminizi Alın</h4>
                        <p>Kısa sürede hazır videonuzu indirin</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scrolling Images Section -->
    <section class="scrolling-section">
        <div class="container-fluid">
            <h2>Son Üretilen Filmler</h2>
            <div class="scroll-wrapper">
                <div class="scroll-container">
                    @php
                        $scrollingImages = \App\Models\ScrollingImage::active()->ordered()->get();
                        // İkinci kez tekrarla (sonsuz döngü efekti için)
                        $allImages = $scrollingImages->concat($scrollingImages);
                    @endphp
                    @foreach($allImages as $image)
                        <div class="scroll-item">
                            @if($image->link)
                                <a href="{{ $image->link }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}">
                                    <div class="scroll-overlay">
                                        <h5>{{ $image->title }}</h5>
                                    </div>
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}">
                                <div class="scroll-overlay">
                                    <h5>{{ $image->title }}</h5>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2>Sıkça Sorulan Sorular</h2>
            @php
                $faqs = \App\Models\Faq::active()->ordered()->get();
            @endphp
            @foreach($faqs as $faq)
                <div class="faq-item" onclick="this.classList.toggle('active')">
                    <div class="faq-question">
                        <span>{{ $faq->question }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        {{ $faq->answer }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Sol: İyzico Logo -->
                <div class="col-md-6 text-center text-md-start mb-3">
                    <img src="{{ asset('images/iyzico-logo.png') }}"
                         alt="iyzico"
                         class="footer-logo">
                    <p class="text-muted mt-2">Güvenli ödeme altyapısı</p>
                </div>
                <!-- Sağ: Yasal Linkler -->
                <div class="col-md-6">
                    <div class="footer-links text-center text-md-end">
                        <a href="{{ route('legal.terms') }}">
                            <i class="bi bi-file-text"></i> Gizlilik Politikası
                        </a>
                        <a href="{{ route('legal.copyright') }}">
                            <i class="bi bi-shield-check"></i> Telif Hakları
                        </a>
                        <a href="{{ route('legal.kvkk') }}">
                            <i class="bi bi-lock"></i> KVKK
                        </a>
                        <a href="{{ route('legal.personal-data') }}">
                            <i class="bi bi-person-lock"></i> Kişisel Verilerin Korunması
                        </a>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0 1rem;">
            <div class="text-center text-muted">
                <p>&copy; {{ date('Y') }} Aytun Film AI. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Sticky Footer Bar -->
    <div class="sticky-footer-bar">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                @auth
                    <a href="{{ route('requests.create') }}" class="btn btn-create">
                        <i class="bi bi-film"></i> Film Talebi Oluştur
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-create">
                        <i class="bi bi-film"></i> Film Talebi Oluştur
                    </a>
                @endauth

                <a href="{{ route('packages.index') }}" class="btn btn-token">
                    <i class="bi bi-coin"></i> Token Satın Al
                </a>

                @php
                    $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number', '+905551234567');
                    $whatsappLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsappNumber);
                @endphp
                <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-whatsapp">
                    <i class="bi bi-whatsapp"></i> WhatsApp Destek
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
