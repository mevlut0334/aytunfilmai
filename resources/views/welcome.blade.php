<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aytun Film AI</title>
    <meta name="description" content="{{ __('welcome.meta_description') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #00D9FF;
            --primary-dark: #0099CC;
            --secondary: #FF006E;
            --accent: #8338EC;
            --bg-dark: #000000;
            --bg-medium: #0A0A0A;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        /* Slider */
        .slider-section {
            margin-top: 76px;
        }

        .carousel-item {
            background: #000;
        }

        .carousel-item img {
            width: 100%;
            height: 550px;
            object-fit: cover;
            filter: brightness(0.8);
            display: block;
        }

        /* Caption ALTTA, görsel dışında */
        .carousel-caption-box {
            background: rgba(0, 0, 0, 0.85);
            border-top: 1px solid rgba(0, 217, 255, 0.3);
            padding: 1.25rem 2rem;
            text-align: center;
        }

        .carousel-caption-box h2 {
            font-size: 1.4rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0.5rem;
        }

        .carousel-caption-box .btn {
            padding: 0.4rem 1.5rem;
            font-size: 0.9rem;
        }

        .carousel-control-prev,
        .carousel-control-next {
            top: 0;
            bottom: auto;
            height: 550px;
        }

        .carousel-indicators { display: none !important; }

        /* App Download Section */
        .app-section {
            padding: 4rem 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0a2e 100%);
            border-top: 1px solid rgba(131, 56, 236, 0.3);
            border-bottom: 1px solid rgba(131, 56, 236, 0.3);
        }

        .app-section h2 {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.75rem;
        }

        .app-section p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .app-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.75rem;
            border-radius: 15px;
            font-weight: bold;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid;
        }

        .app-btn-android {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border-color: #25D366;
            color: white;
        }

        .app-btn-android:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .app-btn-ios {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border-color: var(--primary);
            color: white;
        }

        .app-btn-ios:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 217, 255, 0.4);
            color: white;
        }

        .app-btn i {
            font-size: 1.8rem;
        }

        .app-btn-text small {
            display: block;
            font-size: 0.7rem;
            font-weight: normal;
            opacity: 0.7;
        }

        .app-mockup {
            max-width: 260px;
            filter: drop-shadow(0 0 30px rgba(0, 217, 255, 0.3));
        }

        /* How It Works */
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

        .step-card h4 { color: white; font-weight: bold; }
        .step-card p { color: rgba(255,255,255,0.8); font-size: 1rem; }

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
            cursor: grab;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--secondary) rgba(255,255,255,0.1);
        }

        .scroll-wrapper::-webkit-scrollbar { height: 8px; }
        .scroll-wrapper::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .scroll-wrapper::-webkit-scrollbar-thumb { background: var(--secondary); border-radius: 10px; }
        .scroll-wrapper:active { cursor: grabbing; }

        .scroll-container {
            display: flex;
            gap: 2rem;
            animation: scroll 30s linear infinite;
            width: fit-content;
        }

        .scroll-wrapper:hover .scroll-container { animation-play-state: paused; }

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

        .scroll-item img { width: 100%; height: 400px; object-fit: cover; }

        .scroll-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            padding: 1.5rem;
            color: white;
        }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* FAQ */
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
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(131,56,236,0.2);
            margin-bottom: 1rem;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
        }

        .faq-item:hover {
            border-color: var(--accent);
            box-shadow: 0 5px 15px rgba(131,56,236,0.2);
        }

        .faq-question {
            padding: 1.25rem 1.5rem;
            background: rgba(131,56,236,0.1);
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-question i { transition: transform 0.3s; color: #fff; }
        .faq-item.active .faq-question { background: rgba(131,56,236,0.3); }
        .faq-item.active .faq-question i { transform: rotate(180deg); }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0 1.5rem;
            background: rgba(0,0,0,0.3);
            color: rgba(255,255,255,0.9);
        }

        .faq-item.active .faq-answer { max-height: 500px; padding: 1.25rem 1.5rem; }

        /* Footer */
        .footer {
            background: var(--bg-dark);
            border-top: 1px solid rgba(0,217,255,0.2);
            padding: 3rem 0 5rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.75rem;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .footer-links a:hover { color: var(--primary); padding-left: 10px; }

        /* Sticky Footer Bar */
        .sticky-footer-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.95);
            backdrop-filter: blur(10px);
            border-top: 2px solid var(--primary);
            padding: 1rem 0;
            z-index: 1000;
            box-shadow: 0 -5px 20px rgba(0,217,255,0.3);
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
            border: none; color: white;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,0,110,0.5);
            color: white;
        }

        .btn-token {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none; color: white;
        }

        .btn-token:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,217,255,0.5);
            color: white;
        }

        .btn-whatsapp {
            background: #25D366;
            border: none; color: white;
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(37,211,102,0.5);
            background: #20BA5A; color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .slider-section { margin-top: 56px; }
            .carousel-item img { height: 250px; }
            .carousel-control-prev, .carousel-control-next { height: 250px; }
            .carousel-caption-box h2 { font-size: 1rem; }
            .how-it-works h2, .scrolling-section h2, .faq-section h2 { font-size: 2rem; }
            .app-section h2 { font-size: 1.8rem; }
            .app-btn { padding: 0.75rem 1.25rem; font-size: 0.9rem; }
            .step-card { padding: 1rem; }
            .step-number { font-size: 2rem; }
            .scroll-item { flex: 0 0 200px; }
            .scroll-item img { height: 250px; }
            .sticky-footer-bar .btn { font-size: 0.75rem; padding: 0.5rem 0.75rem; margin: 0.25rem; }
            .footer { padding: 2rem 0 5rem; }
        }

        @media (max-width: 576px) {
            .sticky-footer-bar { padding: 0.75rem 0; }
            .sticky-footer-bar .btn { font-size: 0.7rem; padding: 0.4rem 0.6rem; margin: 0.2rem; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-film"></i> {{ __('welcome.brand') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articles.index') }}">
                            <i class="bi bi-newspaper"></i> {{ __('welcome.blog') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('welcome.packages') }}
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('requests.index') }}">
                                <i class="bi bi-film"></i> {{ __('welcome.my_requests') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.profile') }}">
                                <i class="bi bi-person-circle"></i> {{ __('welcome.my_profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="text-decoration: none;">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('welcome.logout') }}
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> {{ __('welcome.login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-neon ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> {{ __('welcome.register') }}
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Slider Section -->
    <section class="slider-section">
        @php
            $sliders = \App\Models\Slider::active()->ordered()->get();
        @endphp
        @if($sliders->count() > 0)
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($sliders as $index => $slider)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $slider->image) }}"
                             class="d-block w-100"
                             alt="{{ $slider->title }}">
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
        {{-- Caption aktif slide'a göre JS ile güncellenir --}}
        <div class="carousel-caption-box" id="sliderCaption">
            <h2 id="sliderTitle">{{ $sliders->first()->title }}</h2>
            @if($sliders->first()->link)
                <a href="{{ $sliders->first()->link }}" class="btn btn-neon mt-2" id="sliderLink">
                    {{ __('welcome.slider_more') }} <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>
        @endif
    </section>

    <!-- App Download Section -->
    <section class="app-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <h2>{{ __('welcome.app_title') }}</h2>
                    <p>{{ __('welcome.app_subtitle') }}</p>
                    <div class="d-flex flex-wrap gap-3">
                        {{-- Android --}}
                        @php $androidLink = \App\Models\SiteSetting::get('android_app_link', '#'); @endphp
                        <a href="{{ $androidLink }}" class="app-btn app-btn-android" target="_blank">
                            <i class="bi bi-google-play"></i>
                            <div class="app-btn-text">
                                <small>{{ __('welcome.android_btn') }}</small>
                                <strong>Google Play</strong>
                            </div>
                        </a>
                        {{-- iOS --}}
                        @php $iosLink = \App\Models\SiteSetting::get('ios_app_link', '#'); @endphp
                        <a href="{{ $iosLink }}" class="app-btn app-btn-ios" target="_blank">
                            <i class="bi bi-apple"></i>
                            <div class="app-btn-text">
                                <small>{{ __('welcome.ios_btn') }}</small>
                                <strong>App Store</strong>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="{{ asset('images/app-mockup.png') }}"
                         alt="App Mockup"
                         class="app-mockup img-fluid"
                         onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container">
            <h2>{{ __('welcome.how_title') }}</h2>
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4 class="mt-3">{{ __('welcome.step1_title') }}</h4>
                        <p>{{ __('welcome.step1_text') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4 class="mt-3">{{ __('welcome.step2_title') }}</h4>
                        <p>{{ __('welcome.step2_text') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4 class="mt-3">{{ __('welcome.step3_title') }}</h4>
                        <p>{{ __('welcome.step3_text') }}</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4 class="mt-3">{{ __('welcome.step4_title') }}</h4>
                        <p>{{ __('welcome.step4_text') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scrolling Images Section -->
    <section class="scrolling-section">
        <div class="container-fluid">
            <h2>{{ __('welcome.scroll_title') }}</h2>
            <div class="scroll-wrapper">
                <div class="scroll-container">
                    @php
                        $scrollingImages = \App\Models\ScrollingImage::active()->ordered()->get();
                        $allImages = $scrollingImages->concat($scrollingImages);
                    @endphp
                    @foreach($allImages as $image)
                        <div class="scroll-item">
                            @if($image->link)
                                <a href="{{ $image->link }}">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}">
                                    <div class="scroll-overlay"><h5>{{ $image->title }}</h5></div>
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->title }}">
                                <div class="scroll-overlay"><h5>{{ $image->title }}</h5></div>
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
            <h2>{{ __('welcome.faq_title') }}</h2>
            @php $faqs = \App\Models\Faq::active()->ordered()->get(); @endphp
            @foreach($faqs as $faq)
                <div class="faq-item" onclick="this.classList.toggle('active')">
                    <div class="faq-question">
                        <span>{{ $faq->localized_question }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">{{ $faq->localized_answer }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-md-8 text-center">
                    <div class="footer-links">
                        <div class="d-flex flex-wrap justify-content-center gap-4">
                            <a href="{{ route('legal.terms') }}">
                                <i class="bi bi-file-text"></i> {{ __('welcome.legal_terms') }}
                            </a>
                            <a href="{{ route('legal.copyright') }}">
                                <i class="bi bi-shield-check"></i> {{ __('welcome.legal_copyright') }}
                            </a>
                            <a href="{{ route('legal.kvkk') }}">
                                <i class="bi bi-lock"></i> {{ __('welcome.legal_kvkk') }}
                            </a>
                            <a href="{{ route('legal.personal-data') }}">
                                <i class="bi bi-person-lock"></i> {{ __('welcome.legal_personal') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">
            <div class="text-center text-muted">
                <p>&copy; {{ date('Y') }} {{ __('welcome.brand') }}. {{ __('welcome.footer_rights') }}</p>
            </div>
        </div>
    </footer>

    <!-- Sticky Footer Bar -->
    <div class="sticky-footer-bar">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                @auth
                    <a href="{{ route('requests.create') }}" class="btn btn-create">
                        <i class="bi bi-film"></i> {{ __('welcome.create_request') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-create">
                        <i class="bi bi-film"></i> {{ __('welcome.create_request') }}
                    </a>
                @endauth
                <a href="{{ route('packages.index') }}" class="btn btn-token">
                    <i class="bi bi-coin"></i> {{ __('welcome.buy_token') }}
                </a>
                @php
                    $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number', '+905551234567');
                    $whatsappLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsappNumber);
                @endphp
                <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-whatsapp">
                    <i class="bi bi-whatsapp"></i> {{ __('welcome.whatsapp') }}
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Slider caption güncelleme
        @php
            $sliderData = $sliders->map(fn($s) => ['title' => $s->title, 'link' => $s->link])->toArray();
        @endphp
        const sliderData = @json($sliderData);

        const carousel = document.getElementById('mainCarousel');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function (e) {
                const data = sliderData[e.to];
                document.getElementById('sliderTitle').textContent = data.title || '';
                const linkEl = document.getElementById('sliderLink');
                if (linkEl) {
                    linkEl.href = data.link || '#';
                    linkEl.style.display = data.link ? 'inline-flex' : 'none';
                }
            });
        }
    </script>
</body>
</html>
