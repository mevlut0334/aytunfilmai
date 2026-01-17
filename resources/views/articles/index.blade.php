<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>Blog - Aytun Film AI | Yapay Zeka Film Üretimi Rehberi</title>
    <meta name="description" content="Yapay zeka ile film üretimi hakkında en güncel rehberler, ipuçları ve haberler. AI video üretimi teknikleri ve trendleri.">

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

        /* Hero Section */
        .blog-hero {
            padding: 8rem 0 4rem;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-medium) 100%);
            text-align: center;
        }

        .blog-hero h1 {
            font-size: 3.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .blog-hero p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Categories */
        .categories-section {
            padding: 2rem 0;
            background: var(--bg-medium);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .category-pill {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            margin: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 50px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .category-pill:hover, .category-pill.active {
            background: var(--primary);
            color: var(--bg-dark);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* Articles Grid */
        .articles-section {
            padding: 4rem 0;
            background: var(--bg-dark);
            min-height: 60vh;
        }

        .article-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .article-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
        }

        .article-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: var(--bg-medium);
        }

        .article-body {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .article-category {
            display: inline-block;
            padding: 0.25rem 1rem;
            background: rgba(0, 217, 255, 0.2);
            color: var(--primary);
            border-radius: 50px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .article-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .article-excerpt {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .article-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .read-more {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .read-more:hover {
            color: var(--secondary);
        }

        /* Pagination */
        .pagination {
            margin-top: 3rem;
        }

        .page-link {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.3);
            color: white;
            margin: 0 0.25rem;
            border-radius: 8px;
        }

        .page-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--bg-dark);
        }

        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--bg-dark);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 5rem 0;
        }

        .empty-state i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .empty-state h3 {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1rem;
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
            .blog-hero {
                padding: 5rem 0 3rem;
            }

            .blog-hero h1 {
                font-size: 2rem;
            }

            .blog-hero p {
                font-size: 1rem;
            }

            .article-card {
                margin-bottom: 2rem;
            }

            .article-image {
                height: 180px;
            }

            .article-title {
                font-size: 1.25rem;
            }

            .category-pill {
                padding: 0.4rem 1rem;
                margin: 0.3rem;
                font-size: 0.85rem;
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

    <!-- Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <h1>Blog</h1>
            <p>Yapay zeka ile film üretimi hakkında en güncel rehberler, ipuçları ve haberler</p>
        </div>
    </section>

    <!-- Categories -->
    @if($categories->count() > 0)
        <section class="categories-section">
            <div class="container text-center">
                <a href="{{ route('articles.index') }}"
                   class="category-pill {{ !$currentCategory ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap"></i> Tümü
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('articles.index', ['category' => $category->slug]) }}"
                       class="category-pill {{ $currentCategory && $currentCategory->id == $category->id ? 'active' : '' }}">
                        {{ $category->name }} ({{ $category->articles_count }})
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Articles Grid -->
    <section class="articles-section">
        <div class="container">
            @if($articles->count() > 0)
                <div class="row g-4">
                    @foreach($articles as $article)
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="article-card">
                                @if($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}"
                                         alt="{{ $article->image_alt ?? $article->title }}"
                                         class="article-image"
                                         loading="lazy">
                                @else
                                    <div class="article-image d-flex align-items-center justify-content-center">
                                        <i class="bi bi-newspaper" style="font-size: 3rem; color: rgba(255,255,255,0.2);"></i>
                                    </div>
                                @endif

                                <div class="article-body">
                                    @if($article->category)
                                        <span class="article-category">{{ $article->category->name }}</span>
                                    @endif

                                    <h2 class="article-title">{{ $article->title }}</h2>

                                    @if($article->excerpt)
                                        <p class="article-excerpt">{{ Str::limit($article->excerpt, 120) }}</p>
                                    @endif

                                    <div class="article-meta">
                                        <span>
                                            <i class="bi bi-calendar"></i>
                                            {{ $article->published_at->format('d.m.Y') }}
                                        </span>
                                        <span>
                                            <i class="bi bi-eye"></i>
                                            {{ $article->views }}
                                        </span>
                                    </div>

                                    <div class="mt-3">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="read-more">
                                            Devamını Oku <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>Henüz makale yok</h3>
                    <p class="text-muted">
                        @if($currentCategory)
                            Bu kategoride henüz makale bulunmuyor.
                        @else
                            Yakında harika içeriklerle buradayız!
                        @endif
                    </p>
                    @if($currentCategory)
                        <a href="{{ route('articles.index') }}" class="btn btn-neon mt-3">
                            <i class="bi bi-arrow-left"></i> Tüm Makalelere Dön
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

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
