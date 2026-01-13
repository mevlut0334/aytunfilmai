<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profilim - Aytun Film AI</title>

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

        /* Navbar - Welcome ile aynı + Token & Dropdown */
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

        /* Token Badge */
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

        /* Dropdown */
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
        .profile-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        /* Welcome Card */
        .welcome-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.1);
        }

        .profile-icon {
            font-size: 5rem;
            color: var(--primary);
            text-shadow: 0 0 30px rgba(0, 217, 255, 0.5);
        }

        .welcome-card h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-info {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
        }

        /* Token Display */
        .token-display {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            padding: 2rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(255, 0, 110, 0.3);
        }

        .token-display h6 {
            margin: 0 0 0.5rem 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .token-display h1 {
            margin: 0;
            font-size: 3rem;
            font-weight: bold;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 217, 255, 0.5);
            color: white;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
            transition: all 0.3s;
        }

        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
            color: white;
        }

        .btn-info-custom {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
        }

        .btn-info-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 0, 110, 0.5);
            color: white;
        }

        /* Info Cards */
        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.1);
        }

        .info-card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 1.25rem 1.5rem;
        }

        .info-card-header h5 {
            margin: 0;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .info-card-body {
            padding: 1.5rem;
        }

        .info-list-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 1.5rem;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }

        .info-list-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(0, 217, 255, 0.3);
        }

        .info-list-item:last-child {
            margin-bottom: 0;
        }

        .badge-admin {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: bold;
        }

        .badge-user {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: bold;
        }

        /* Token Card */
        .token-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.1);
        }

        .token-card h2 {
            color: #10b981;
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
        }

        /* Alert */
        .alert-custom {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 15px;
            color: white;
            padding: 1.5rem;
        }

        .alert-custom h5 {
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-container {
                padding: 5rem 1rem 2rem;
            }

            .welcome-card {
                padding: 2rem 1.5rem;
            }

            .profile-icon {
                font-size: 3.5rem;
            }

            .token-display h1 {
                font-size: 2rem;
            }

            .btn-primary-custom,
            .btn-success-custom,
            .btn-info-custom {
                width: 100%;
                margin-bottom: 0.5rem;
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

                    <!-- Token Badge with Cart Icon -->
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="token-badge">
                            <i class="bi bi-coin"></i>
                            <span>{{ number_format($user->token_balance, 0) }}</span>
                            <i class="bi bi-cart3"></i>
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ $user->name }}
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

    <!-- Profile Content -->
    <div class="profile-container">
        <div class="container">
            <!-- Welcome Card -->
            <div class="welcome-card">
                <i class="bi bi-person-circle profile-icon"></i>
                <h2>Hoş Geldiniz, {{ $user->name }}!</h2>
                <p class="user-info">
                    <i class="bi bi-envelope"></i> {{ $user->email }} |
                    <i class="bi bi-phone"></i> {{ $user->phone }}
                </p>

                <!-- Token Display -->
                <div class="token-display">
                    <h6>Token Bakiyeniz</h6>
                    <h1>
                        <i class="bi bi-coin"></i> {{ number_format($user->token_balance, 0) }}
                    </h1>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('user.edit') }}" class="btn btn-primary-custom">
                        <i class="bi bi-pencil"></i> Profili Düzenle
                    </a>
                    <a href="{{ route('requests.create') }}" class="btn btn-success-custom">
                        <i class="bi bi-plus-circle"></i> Yeni Film Talebi
                    </a>
                    <a href="{{ route('packages.index') }}" class="btn btn-info-custom">
                        <i class="bi bi-bag-plus"></i> Token Satın Al
                    </a>
                </div>
            </div>

            <!-- Info Cards Row -->
            <div class="row">
                <!-- Account Info -->
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5><i class="bi bi-info-circle"></i> Hesap Bilgileri</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-list-item">
                                <strong>Ad Soyad:</strong>
                                <span>{{ $user->name }}</span>
                            </div>
                            <div class="info-list-item">
                                <strong>E-posta:</strong>
                                <span>{{ $user->email }}</span>
                            </div>
                            <div class="info-list-item">
                                <strong>Telefon:</strong>
                                <span>{{ $user->phone }}</span>
                            </div>
                            <div class="info-list-item">
                                <strong>Kayıt Tarihi:</strong>
                                <span>{{ $user->created_at->format('d.m.Y') }}</span>
                            </div>
                            <div class="info-list-item">
                                <strong>Hesap Tipi:</strong>
                                <span>
                                    @if($user->is_admin)
                                        <span class="badge-admin">Admin</span>
                                    @else
                                        <span class="badge-user">Kullanıcı</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Token Info -->
                <div class="col-md-6">
                    <div class="token-card">
                        <h6 style="color: rgba(255,255,255,0.7); margin-bottom: 1rem;">
                            <i class="bi bi-coin"></i> Token Bilgileri
                        </h6>
                        <h2>{{ number_format($user->token_balance, 0) }}</h2>
                        <p style="color: rgba(255,255,255,0.6); margin-bottom: 2rem;">Mevcut Token Bakiyeniz</p>
                        <a href="{{ route('packages.index') }}" class="btn btn-success-custom">
                            <i class="bi bi-bag-plus"></i> Token Satın Al
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="row">
                <div class="col-12">
                    <div class="alert-custom">
                        <h5><i class="bi bi-info-circle-fill"></i> Bilgilendirme</h5>
                        <p class="mb-0">
                            Hesabınız başarıyla oluşturuldu! Film oluşturmak için token bakiyeniz 0 olmamalıdır.
                            Profil bilgilerinizi düzenlemek için "Profili Düzenle" butonuna tıklayabilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
