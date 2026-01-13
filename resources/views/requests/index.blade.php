<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Taleplerim - Aytun Film AI</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-dark);
            color: white;
            min-height: 100vh;
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

        .token-badge {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            margin-right: 1rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .token-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 217, 255, 0.5);
            color: white;
        }

        .token-badge i {
            font-size: 1.2rem;
        }

        .dropdown-menu {
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 15px;
            padding: 0.5rem;
            backdrop-filter: blur(10px);
        }

        .dropdown-item {
            color: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background: rgba(0, 217, 255, 0.2);
            color: var(--primary);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            width: 20px;
        }

        /* Content */
        .requests-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin: 0;
        }

        .btn-new-request {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
            transition: all 0.3s;
        }

        .btn-new-request:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 217, 255, 0.5);
            color: white;
        }

        /* Empty State */
        .empty-state {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.5;
        }

        .empty-state h3 {
            color: white;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2rem;
        }

        /* Request Cards */
        .request-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.1);
            transition: all 0.3s;
            position: relative;
        }

        .request-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
        }

        .status-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
        }

        .badge-pending {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .badge-processing {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
        }

        .badge-completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
        }

        .badge-failed {
            background: linear-gradient(135deg, var(--secondary) 0%, #dc3545 100%);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
        }

        /* Card */
        .request-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.1);
            transition: all 0.3s;
        }

        .request-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.3);
        }

        .request-card img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .request-card .placeholder {
            height: 200px;
            background: rgba(255, 255, 255, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .request-card .placeholder i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.2);
        }

        .card-body-custom {
            padding: 1.5rem;
        }

        .card-title-custom {
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }

        .card-text-custom {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .card-info {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .card-info i {
            color: var(--primary);
            margin-right: 0.25rem;
        }

        /* Buttons */
        .btn-detail {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .btn-detail:hover {
            opacity: 0.9;
            color: white;
        }

        .btn-delete {
            background: rgba(255, 0, 110, 0.1);
            border: 1px solid rgba(255, 0, 110, 0.3);
            color: var(--secondary);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-delete:hover {
            background: rgba(255, 0, 110, 0.2);
            color: var(--secondary);
        }

        /* Empty State */
        .empty-state {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 6rem;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .empty-state h3 {
            color: white;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2rem;
        }

        .btn-create-request {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 5px 20px rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
        }

        .btn-create-request:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 0, 110, 0.5);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .requests-container {
                padding: 5rem 1rem 2rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
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
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> Paketler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('requests.index') }}">
                            <i class="bi bi-film"></i> Taleplerim
                        </a>
                    </li>

                    <!-- Token Badge -->
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="token-badge">
                            <i class="bi bi-coin"></i>
                            <span>{{ number_format(auth()->user()->token_balance ?? 0, 0) }}</span>
                            <i class="bi bi-cart3"></i>
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="bi bi-person"></i> Profilim
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag"></i> Siparişlerim
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Çıkış Yap
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Requests Content -->
    <div class="requests-container">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-film"></i> Film Taleplerim</h2>
                    <a href="{{ route('requests.create') }}" class="btn btn-create-request">
                        <i class="bi bi-plus-circle"></i> Yeni Talep
                    </a>
                </div>
            </div>

            @if($requests->isEmpty())
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="bi bi-film"></i>
                    <h3>Henüz Talebiniz Yok</h3>
                    <p>Film talebi oluşturarak yapay zeka ile film üretmeye başlayabilirsiniz.</p>
                    <a href="{{ route('requests.create') }}" class="btn btn-create-request">
                        <i class="bi bi-plus-circle"></i> İlk Talebinizi Oluşturun
                    </a>
                </div>
            @else
                <!-- Request Cards -->
                <div class="row g-4">
                    @foreach($requests as $request)
                        <div class="col-md-6 col-lg-4">
                            <div class="request-card">
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                                    @if($request->status === 'pending')
                                        <span class="badge" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); padding: 0.5rem 1rem; border-radius: 50px; font-weight: bold;">
                                            Beklemede
                                        </span>
                                    @elseif($request->status === 'processing')
                                        <span class="badge" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 0.5rem 1rem; border-radius: 50px; font-weight: bold;">
                                            İşleniyor
                                        </span>
                                    @elseif($request->status === 'completed')
                                        <span class="badge badge-completed">
                                            Tamamlandı
                                        </span>
                                    @else
                                        <span class="badge badge-failed">
                                            Başarısız
                                        </span>
                                    @endif
                                </div>

                                <!-- Thumbnail -->
                                @if($request->characters->count() > 0 && $request->characters->first()->images->count() > 0)
                                    <img src="{{ asset('storage/' . $request->characters->first()->images->first()->image_path) }}"
                                         alt="{{ $request->title }}">
                                @else
                                    <div class="placeholder">
                                        <i class="bi bi-film"></i>
                                    </div>
                                @endif

                                <div class="card-body-custom">
                                    <!-- Title -->
                                    <h5 class="card-title-custom">{{ Str::limit($request->title, 50) }}</h5>

                                    <!-- Description -->
                                    <p class="card-text-custom">
                                        {{ Str::limit($request->description, 100) }}
                                    </p>

                                    <!-- Info -->
                                    <div class="card-info">
                                        <div>
                                            <i class="bi bi-calendar"></i> {{ $request->created_at->format('d.m.Y H:i') }}
                                        </div>
                                        @if($request->characters->count() > 0)
                                            <div class="mt-1">
                                                <i class="bi bi-people"></i> {{ $request->characters->count() }} Karakter
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Buttons -->
                                    <div>
                                        <a href="{{ route('requests.show', $request->id) }}" class="btn btn-detail">
                                            <i class="bi bi-eye"></i> Detay
                                        </a>

                                        @if($request->status === 'pending' || $request->status === 'failed')
                                            <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                                                  onsubmit="return confirm('Bu talebi silmek istediğinize emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="bi bi-trash"></i> Sil
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
