<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Talep Detayı - Aytun Film AI</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">

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
        .detail-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Cards */
        .card-custom {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 1.25rem 1.5rem;
        }

        .card-header-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 1.25rem 1.5rem;
        }

        .card-header-warning {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            padding: 1.25rem 1.5rem;
        }

        .card-header-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 1.25rem 1.5rem;
        }

        .card-header-danger {
            background: linear-gradient(135deg, var(--secondary) 0%, #dc3545 100%);
            padding: 1.25rem 1.5rem;
        }

        .card-header-dark {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            padding: 1.25rem 1.5rem;
        }

        .card-header-custom h5,
        .card-header-custom h6 {
            margin: 0;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .card-body-custom {
            padding: 1.5rem;
        }

        /* Status Badges */
        .badge-pending {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
        }

        .badge-processing {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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

        /* Video Ready Card */
        .video-ready-card {
            text-align: center;
            padding: 3rem 2rem;
        }

        .video-ready-card i {
            font-size: 5rem;
            color: #10b981;
        }

        .video-ready-card h4 {
            color: white;
            margin-bottom: 2rem;
        }

        .btn-download {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
            transition: all 0.3s;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.5);
            color: white;
        }

        /* Character Images */
        .character-section {
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .character-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .character-section h6 {
            color: white;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .character-image {
            cursor: pointer;
            height: 150px;
            width: 100%;
            object-fit: cover;
            border-radius: 15px;
            border: 2px solid rgba(0, 217, 255, 0.2);
            transition: all 0.3s;
        }

        .character-image:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
            transform: scale(1.05);
        }

        /* Alert */
        .alert-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 15px;
            color: white;
            padding: 1rem;
        }

        .alert-custom strong {
            color: #ef4444;
        }

        .alert-info-custom {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 15px;
            color: white;
            padding: 1rem;
        }

        .alert-info-custom i {
            color: var(--primary);
        }

        /* Buttons */
        .btn-delete-request {
            background: linear-gradient(135deg, var(--secondary) 0%, #dc3545 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
            width: 100%;
        }

        .btn-delete-request:hover {
            opacity: 0.9;
            color: white;
        }

        /* Info Card */
        .info-card {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 20px;
            padding: 1.5rem;
        }

        .info-card h6 {
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .info-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-card ul li {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .info-card ul li:last-child {
            margin-bottom: 0;
        }

        .info-card ul li strong {
            color: white;
        }

        /* Status Info */
        .status-info small {
            color: rgba(255, 255, 255, 0.6);
            display: block;
            margin-bottom: 0.25rem;
        }

        .status-info h5 {
            color: white;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .detail-container {
                padding: 5rem 1rem 2rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .character-image {
                height: 120px;
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

    <!-- Detail Content -->
    <div class="detail-container">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <a href="{{ route('requests.index') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Taleplerime Dön
                </a>
                <h2><i class="bi bi-film"></i> {{ $request->title }}</h2>
            </div>

            <div class="row">
                <!-- Left: Request Info -->
                <div class="col-lg-8">
                    <!-- Status Card -->
                    <div class="card-custom">
                        <div class="card-header-{{ $request->status === 'completed' ? 'success' : ($request->status === 'processing' ? 'info' : ($request->status === 'pending' ? 'warning' : 'danger')) }} card-header-custom">
                            <h5><i class="bi bi-info-circle"></i> Talep Durumu</h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-6 mb-3 status-info">
                                    <small>Durum</small>
                                    <h5>
                                        @if($request->status === 'pending')
                                            <span class="badge-pending">Beklemede</span>
                                        @elseif($request->status === 'processing')
                                            <span class="badge-processing">İşleniyor</span>
                                        @elseif($request->status === 'completed')
                                            <span class="badge-completed">Tamamlandı</span>
                                        @else
                                            <span class="badge-failed">Başarısız</span>
                                        @endif
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3 status-info">
                                    <small>Oluşturma Tarihi</small>
                                    <h5>{{ $request->created_at->format('d.m.Y H:i') }}</h5>
                                </div>
                            </div>

                            @if($request->status === 'failed' && $request->error_message)
                                <div class="alert-custom mt-3">
                                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Hata:</strong>
                                    <p class="mb-0 mt-2">{{ $request->error_message }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Video Ready -->
                    @if($request->status === 'completed' && $request->video_url)
                        <div class="card-custom">
                            <div class="card-header-success card-header-custom">
                                <h5><i class="bi bi-check-circle"></i> Film Hazır!</h5>
                            </div>
                            <div class="card-body-custom video-ready-card">
                                <i class="bi bi-film mb-4"></i>
                                <h4>Filminiz başarıyla oluşturuldu!</h4>
                                <a href="{{ $request->video_url }}" class="btn btn-download" download>
                                    <i class="bi bi-download"></i> Filmi İndir
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Characters -->
                    @if($request->characters->count() > 0)
                        <div class="card-custom">
                            <div class="card-header-success card-header-custom">
                                <h5><i class="bi bi-people"></i> Karakterler ({{ $request->characters->count() }})</h5>
                            </div>
                            <div class="card-body-custom">
                                @foreach($request->characters as $character)
                                    <div class="character-section">
                                        <h6><i class="bi bi-person-fill"></i> {{ $character->name }}</h6>

                                        <!-- Character Images -->
                                        <div class="row g-3">
                                            @foreach($character->images as $image)
                                                <div class="col-6 col-md-4 col-lg-3">
                                                    <a href="{{ asset('storage/' . $image->image_path) }}"
                                                       data-lightbox="character-{{ $character->id }}"
                                                       data-title="{{ $character->name }} - Görsel {{ $image->order }}">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                             class="character-image"
                                                             alt="{{ $character->name }} - {{ $image->order }}">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small style="color: rgba(255, 255, 255, 0.6); display: block; margin-top: 1rem;">
                                            {{ $character->images->count() }} görsel
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="card-custom">
                        <div class="card-header-primary card-header-custom">
                            <h5><i class="bi bi-textarea-t"></i> Film Açıklaması</h5>
                        </div>
                        <div class="card-body-custom">
                            <p style="white-space: pre-wrap; color: rgba(255, 255, 255, 0.9); margin: 0;">{{ $request->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Actions & Info -->
                <div class="col-lg-4">
                    <!-- Actions Card -->
                    <div class="card-custom">
                        <div class="card-header-dark card-header-custom">
                            <h6><i class="bi bi-gear"></i> İşlemler</h6>
                        </div>
                        <div class="card-body-custom">
                            @if($request->status === 'pending' || $request->status === 'failed')
                                <form action="{{ route('requests.destroy', $request->id) }}" method="POST"
                                      onsubmit="return confirm('Bu talebi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete-request">
                                        <i class="bi bi-trash"></i> Talebi Sil
                                    </button>
                                </form>
                            @else
                                <div class="alert-info-custom">
                                    <small>
                                        <i class="bi bi-info-circle-fill"></i>
                                        {{ $request->status === 'processing' ? 'İşlem devam ederken' : 'Tamamlanmış' }} talepler silinemez.
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="info-card">
                        <h6><i class="bi bi-info-square-fill"></i> Talep Bilgileri</h6>
                        <ul>
                            <li>
                                <strong>Talep ID:</strong> #{{ $request->id }}
                            </li>
                            <li>
                                <strong>Karakter Sayısı:</strong> {{ $request->characters->count() }}
                            </li>
                            <li>
                                <strong>Toplam Görsel:</strong> {{ $request->totalImages }}
                            </li>
                            <li>
                                <strong>Son Güncelleme:</strong> {{ $request->updated_at->format('d.m.Y H:i') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Lightbox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
</body>
</html>
