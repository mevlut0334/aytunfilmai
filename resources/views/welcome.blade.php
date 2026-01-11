<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aytun Film AI - Yapay Zeka ile Film Üretimi</title>

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
        }

        .carousel-item img {
            height: 600px;
            object-fit: cover;
            filter: brightness(0.8);
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(0, 217, 255, 0.3);
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

        .scroll-container {
            display: flex;
            gap: 2rem;
            animation: scroll 30s linear infinite;
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

        .scroll-container:hover {
            animation-play-state: paused;
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

        .accordion-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(131, 56, 236, 0.2);
            margin-bottom: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }

        .accordion-button {
            background: rgba(131, 56, 236, 0.1);
            color: white;
            font-weight: bold;
            border: none;
        }

        .accordion-button:not(.collapsed) {
            background: rgba(131, 56, 236, 0.3);
            color: var(--accent);
            box-shadow: 0 0 20px rgba(131, 56, 236, 0.3);
        }

        .accordion-button::after {
            filter: invert(1);
        }

        .accordion-body {
            background: rgba(0, 0, 0, 0.3);
            color: rgba(255, 255, 255, 0.8);
        }

        /* Footer */
        .footer {
            background: var(--bg-dark);
            border-top: 1px solid rgba(0, 217, 255, 0.2);
            padding: 3rem 0 1rem;
        }

        .footer-logo {
            max-height: 60px;
            filter: brightness(0) invert(1);
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
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
            .carousel-item img {
                height: 400px;
            }

            .how-it-works h2,
            .scrolling-section h2,
            .faq-section h2 {
                font-size: 2rem;
            }

            .sticky-footer-bar .btn {
                font-size: 0.8rem;
                padding: 0.5rem 1rem;
                margin: 0.2rem;
            }

            .scroll-item {
                flex: 0 0 250px;
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
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4 class="mt-3">Kayıt Ol</h4>
                        <p class="text-muted">Hızlı ve kolay kayıt işlemi ile başla</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4 class="mt-3">Token Satın Al</h4>
                        <p class="text-muted">İhtiyacına göre token paketi seç</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4 class="mt-3">Film Talebi Oluştur</h4>
                        <p class="text-muted">Yapay zeka ile senaryonu yaz</p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="col-md-3">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4 class="mt-3">Filminizi Alın</h4>
                        <p class="text-muted">Kısa sürede hazır videonuzu indirin</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scrolling Images Section -->
    <section class="scrolling-section">
        <div class="container-fluid">
            <h2>Son Üretilen Filmler</h2>
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
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2>Sıkça Sorulan Sorular</h2>
            <div class="accordion" id="faqAccordion">
                @php
                    $faqs = \App\Models\Faq::active()->ordered()->get();
                @endphp
                @foreach($faqs as $index => $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq{{ $faq->id }}"
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Sol: İyzico Logo -->
                <div class="col-md-6 text-center text-md-start mb-3">
                    <img src="https://www.iyzico.com/assets/images/content/logo-white.svg"
                         alt="iyzico"
                         class="footer-logo">
                    <p class="text-muted mt-2">Güvenli ödeme altyapısı</p>
                </div>
                <!-- Sağ: Yasal Linkler -->
                <div class="col-md-6">
                    <div class="footer-links text-center text-md-end">
                        <a href="{{ route('legal.terms') }}">
                            <i class="bi bi-file-text"></i> Kullanım Koşulları
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
