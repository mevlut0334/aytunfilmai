<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ $article->meta_title ?? $article->title }} - Aytun Film AI</title>
    <meta name="description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->content), 155) }}">
    @if($article->meta_keywords)
        <meta name="keywords" content="{{ $article->meta_keywords }}">
    @endif
    <meta name="author" content="{{ $article->user->name }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $article->meta_title ?? $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->content), 155) }}">
    <meta property="og:url" content="{{ route('articles.show', $article->slug) }}">
    @if($article->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $article->featured_image) }}">
    @endif
    <meta property="article:published_time" content="{{ $article->published_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $article->user->name }}">
    @if($article->category)
        <meta property="article:section" content="{{ $article->category->name }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->meta_title ?? $article->title }}">
    <meta name="twitter:description" content="{{ $article->meta_description ?? Str::limit(strip_tags($article->content), 155) }}">
    @if($article->featured_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $article->featured_image) }}">
    @endif

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ route('articles.show', $article->slug) }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #00D9FF;
            --primary-dark: #0099CC;
            --secondary: #FF006E;
            --accent: #8338EC;
            --bg-dark: #000000;
            --bg-medium: #0A0A0A;
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

        /* Article Header */
        .article-header {
            padding: 8rem 0 3rem;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-medium) 100%);
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 2rem;
        }

        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.7);
        }

        .article-category-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(0, 217, 255, 0.2);
            color: var(--primary);
            border-radius: 50px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .article-title {
            font-size: 3rem;
            font-weight: bold;
            line-height: 1.3;
            margin-bottom: 1.5rem;
        }

        .article-meta {
            display: flex;
            gap: 2rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .article-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Featured Image */
        .featured-image-section {
            margin: 3rem 0;
        }

        .featured-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 20px;
            border: 1px solid rgba(0, 217, 255, 0.2);
        }

        /* Article Content */
        .article-content {
            padding: 3rem 0;
            background: var(--bg-dark);
        }

        .content-body {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 217, 255, 0.1);
            border-radius: 20px;
            padding: 3rem;
            font-size: 1.1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
        }

        .content-body h1,
        .content-body h2,
        .content-body h3,
        .content-body h4 {
            color: white;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .content-body h2 {
            font-size: 2rem;
            color: var(--primary);
        }

        .content-body h3 {
            font-size: 1.5rem;
        }

        .content-body p {
            margin-bottom: 1.5rem;
        }

        .content-body a {
            color: var(--primary);
            text-decoration: none;
            border-bottom: 1px solid var(--primary);
            transition: all 0.3s;
        }

        .content-body a:hover {
            color: var(--secondary);
            border-bottom-color: var(--secondary);
        }

        .content-body ul,
        .content-body ol {
            margin: 1.5rem 0;
            padding-left: 2rem;
        }

        .content-body li {
            margin-bottom: 0.75rem;
        }

        .content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 2rem 0;
        }

        .content-body blockquote {
            border-left: 4px solid var(--primary);
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: rgba(255, 255, 255, 0.7);
        }

        .content-body code {
            background: rgba(0, 217, 255, 0.1);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            color: var(--primary);
            font-family: 'Courier New', monospace;
        }

        .content-body pre {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 10px;
            overflow-x: auto;
            margin: 2rem 0;
        }

        /* Share Buttons */
        .share-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .share-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .share-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .share-btn:hover {
            transform: translateY(-2px);
            color: white;
        }

        .share-twitter {
            background: #1DA1F2;
        }

        .share-facebook {
            background: #1877F2;
        }

        .share-linkedin {
            background: #0A66C2;
        }

        .share-whatsapp {
            background: #25D366;
        }

        /* Related Articles */
        .related-articles {
            padding: 4rem 0;
            background: var(--bg-medium);
        }

        .related-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
        }

        .related-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.2);
        }

        .related-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .related-body {
            padding: 1.5rem;
        }

        .related-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0.75rem;
        }

        .related-date {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        /* Footer */
        .footer {
            background: var(--bg-dark);
            border-top: 1px solid rgba(0, 217, 255, 0.2);
            padding: 2rem 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .article-header {
                padding: 5rem 0 2rem;
            }

            .article-title {
                font-size: 2rem;
            }

            .content-body {
                padding: 2rem 1.5rem;
                font-size: 1rem;
            }

            .content-body h2 {
                font-size: 1.5rem;
            }

            .article-meta {
                gap: 1rem;
            }

            .share-buttons {
                justify-content: center;
            }

            .share-btn {
                font-size: 0.9rem;
                padding: 0.6rem 1.2rem;
            }

            .related-image {
                height: 150px;
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
                        <a class="nav-link" href="{{ route('home') }}">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('articles.index') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">Paketler</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('requests.index') }}">Taleplerim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.profile') }}">Profilim</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Çıkış</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Giriş</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-neon" href="{{ route('register') }}">Kayıt Ol</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Article Header -->
    <section class="article-header">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i> Ana Sayfa
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('articles.index') }}">Blog</a>
                    </li>
                    @if($article->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('articles.index', ['category' => $article->category->slug]) }}">
                                {{ $article->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ Str::limit($article->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Category Badge -->
            @if($article->category)
                <span class="article-category-badge">
                    <i class="bi bi-folder"></i> {{ $article->category->name }}
                </span>
            @endif

            <!-- Title -->
            <h1 class="article-title">{{ $article->title }}</h1>

            <!-- Meta Info -->
            <div class="article-meta">
                <div class="article-meta-item">
                    <i class="bi bi-person"></i>
                    <span>{{ $article->user->name }}</span>
                </div>
                <div class="article-meta-item">
                    <i class="bi bi-calendar"></i>
                    <span>{{ $article->published_at->format('d F Y') }}</span>
                </div>
                <div class="article-meta-item">
                    <i class="bi bi-eye"></i>
                    <span>{{ $article->views }} Görüntülenme</span>
                </div>
                <div class="article-meta-item">
                    <i class="bi bi-clock"></i>
                    <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} dk okuma</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    @if($article->featured_image)
        <section class="featured-image-section">
            <div class="container">
                <img src="{{ asset('storage/' . $article->featured_image) }}"
                     alt="{{ $article->image_alt ?? $article->title }}"
                     class="featured-image">
            </div>
        </section>
    @endif

    <!-- Article Content -->
    <section class="article-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Excerpt -->
                    @if($article->excerpt)
                        <div class="alert alert-info" style="background: rgba(0, 217, 255, 0.1); border: 1px solid rgba(0, 217, 255, 0.3); color: white;">
                            <strong>Özet:</strong> {{ $article->excerpt }}
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="content-body">
                        {!! $article->content !!}
                    </div>

                    <!-- Share Section -->
                    <div class="share-section">
                        <h4 class="mb-3">
                            <i class="bi bi-share"></i> Bu Makaleyi Paylaş
                        </h4>
                        <div class="share-buttons">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('articles.show', $article->slug)) }}&text={{ urlencode($article->title) }}"
                               target="_blank"
                               class="share-btn share-twitter">
                                <i class="bi bi-twitter"></i> Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank"
                               class="share-btn share-facebook">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('articles.show', $article->slug)) }}"
                               target="_blank"
                               class="share-btn share-linkedin">
                                <i class="bi bi-linkedin"></i> LinkedIn
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('articles.show', $article->slug)) }}"
                               target="_blank"
                               class="share-btn share-whatsapp">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Articles -->
    @if($relatedArticles->count() > 0)
        <section class="related-articles">
            <div class="container">
                <h2 class="text-center mb-5" style="color: var(--primary); font-size: 2.5rem; font-weight: bold;">
                    İlgili Makaleler
                </h2>
                <div class="row g-4">
                    @foreach($relatedArticles as $related)
                        <div class="col-lg-4 col-md-6 col-12">
                            <a href="{{ route('articles.show', $related->slug) }}" style="text-decoration: none;">
                                <div class="related-card">
                                    @if($related->featured_image)
                                        <img src="{{ asset('storage/' . $related->featured_image) }}"
                                             alt="{{ $related->image_alt ?? $related->title }}"
                                             class="related-image"
                                             loading="lazy">
                                    @else
                                        <div class="related-image d-flex align-items-center justify-content-center" style="background: var(--bg-dark);">
                                            <i class="bi bi-newspaper" style="font-size: 3rem; color: rgba(255,255,255,0.2);"></i>
                                        </div>
                                    @endif
                                    <div class="related-body">
                                        <h3 class="related-title">{{ Str::limit($related->title, 60) }}</h3>
                                        <div class="related-date">
                                            <i class="bi bi-calendar"></i>
                                            {{ $related->published_at->format('d.m.Y') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Aytun Film AI. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
