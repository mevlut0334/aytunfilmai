<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('packages.title') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Paddle.js -->
    @if(config('cashier.sandbox'))
        <script src="https://sandbox-cdn.paddle.com/paddle/v2/paddle.js"></script>
    @else
        <script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
    @endif

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
            min-height: 100vh;
        }

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

        .dropdown-item i { margin-right: 0.5rem; width: 20px; }

        .packages-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 3rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .page-header .lead {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.2rem;
        }

        .package-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.1);
            transition: all 0.3s;
        }

        .package-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 15px 40px rgba(0, 217, 255, 0.3);
        }

        .package-card h3 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            margin-bottom: 1.5rem;
        }

        .token-icon {
            font-size: 4rem;
            color: #FFD700;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .token-amount {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary);
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
            margin-bottom: 0;
        }

        .token-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1rem;
        }

        .package-description {
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            margin-bottom: 1.5rem;
            min-height: 3rem;
        }

        .package-price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #10b981;
            text-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
            margin-bottom: 0.25rem;
        }

        .price-note {
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        .btn-buy {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 1rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 5px 20px rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
            margin-top: auto;
            width: 100%;
            cursor: pointer;
        }

        .btn-buy:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 0, 110, 0.5);
            color: white;
        }

        .btn-buy:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login-required {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.4);
            color: var(--primary);
            padding: 1rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s;
            margin-top: auto;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-login-required:hover {
            background: rgba(0, 217, 255, 0.15);
            color: var(--primary);
        }

        .info-card {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 3rem;
        }

        .info-card h5 {
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .info-card ul {
            color: rgba(255, 255, 255, 0.8);
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-card ul li {
            padding: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
        }

        .info-card ul li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .empty-state {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
        }

        .empty-state i { font-size: 5rem; color: var(--primary); margin-bottom: 1.5rem; }
        .empty-state h4 { color: white; margin-bottom: 1rem; }
        .empty-state p { color: rgba(255, 255, 255, 0.7); }

        /* Loading spinner on button */
        .btn-buy .spinner {
            display: none;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 768px) {
            .packages-container { padding: 5rem 1rem 2rem; }
            .page-header h1 { font-size: 2rem; }
            .page-header .lead { font-size: 1rem; }
            .package-card { padding: 1.5rem; }
            .token-icon { font-size: 3rem; }
            .token-amount { font-size: 2rem; }
            .package-price { font-size: 2rem; }
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
                            <i class="bi bi-box-seam"></i> {{ __('packages.nav_packages') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('requests.index') }}">
                            <i class="bi bi-film"></i> {{ __('packages.nav_requests') }}
                        </a>
                    </li>

                    @auth
                        <li class="nav-item">
                            <span class="token-badge">
                                <i class="bi bi-coin"></i>
                                <span>{{ number_format(auth()->user()->token_balance ?? 0, 0) }}</span>
                            </span>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="bi bi-person"></i> {{ __('packages.nav_profile') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="bi bi-bag"></i> {{ __('packages.nav_orders') }}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> {{ __('packages.nav_logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> {{ __('packages.nav_login') }}
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Packages Content -->
    <div class="packages-container">
        <div class="container">

            <!-- Page Header -->
            <div class="page-header">
                <h1>
                    <i class="bi bi-coin"></i> {{ __('packages.heading') }}
                </h1>
                <p class="lead">{{ __('packages.subheading') }}</p>
            </div>

            @if($packages->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-info-circle"></i>
                    <h4>{{ __('packages.no_packages') }}</h4>
                    <p>{{ __('packages.no_packages_sub') }}</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="package-card">

                                <!-- Paket Adı -->
                                <h3>{{ $package->name }}</h3>

                                <!-- Token -->
                                <div class="text-center mb-3">
                                    <div class="token-icon">
                                        <i class="bi bi-coin"></i>
                                    </div>
                                    <h2 class="token-amount">
                                        {{ number_format($package->token_amount, 0) }}
                                    </h2>
                                    <p class="token-label">{{ __('packages.token_label') }}</p>
                                </div>

                                <!-- Açıklama -->
                                @if($package->description)
                                    <p class="package-description">{{ $package->description }}</p>
                                @endif

                                <!-- Fiyat -->
                                <div class="text-center mb-1">
                                    @if($package->paddle_price)
                                        <h3 class="package-price">
                                            ${{ number_format($package->paddle_price, 2) }}
                                        </h3>
                                        <p class="price-note">USD &bull; {{ __('packages.info_5') }}</p>
                                    @else
                                        <p class="text-warning">{{ __('packages.no_price') }}</p>
                                    @endif
                                </div>

                                <!-- Satın Al / Giriş Yap -->
                                @auth
                                    @if($package->paddle_price_id && $package->paddle_price)
                                        <button
                                            class="btn-buy paddle-buy-btn"
                                            data-price-id="{{ $package->paddle_price_id }}"
                                            data-package-id="{{ $package->id }}"
                                            data-user-email="{{ auth()->user()->email }}"
                                            data-user-name="{{ auth()->user()->name }}"
                                        >
                                            <span class="spinner"></span>
                                            <i class="bi bi-credit-card me-2"></i>
                                            {{ __('packages.buy_button') }}
                                        </button>
                                    @else
                                        <button class="btn-buy" disabled>
                                            {{ __('packages.no_price') }}
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-login-required">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        {{ __('packages.login_required') }}
                                    </a>
                                @endauth

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bilgi Kutusu -->
                <div class="info-card">
                    <h5>
                        <i class="bi bi-info-circle-fill"></i> {{ __('packages.info_title') }}
                    </h5>
                    <ul>
                        <li>{{ __('packages.info_1') }}</li>
                        <li>{{ __('packages.info_2') }}</li>
                        <li>{{ __('packages.info_3') }}</li>
                        <li>{{ __('packages.info_4') }}</li>
                        <li>{{ __('packages.info_5') }}</li>
                    </ul>
                </div>
            @endif

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Paddle başlat
        @if(config('cashier.sandbox'))
            Paddle.Environment.set('sandbox');
        @endif

        Paddle.Initialize({
            token: '{{ config('cashier.client_side_token') }}',
        });

        // Satın Al butonları
        document.querySelectorAll('.paddle-buy-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const priceId   = this.dataset.priceId;
                const userEmail = this.dataset.userEmail;
                const userName  = this.dataset.userName;
                const packageId = this.dataset.packageId;

                // Spinner göster
                const spinner = this.querySelector('.spinner');
                if (spinner) spinner.style.display = 'inline-block';
                this.disabled = true;

                Paddle.Checkout.open({
                    items: [{ priceId: priceId, quantity: 1 }],
                    customer: {
                        email: userEmail,
                    },
                    customData: {
                        user_id:    '{{ auth()->id() ?? '' }}',
                        package_id: packageId,
                    },
                    settings: {
                        displayMode: 'overlay',
                        theme: 'dark',
                        locale: '{{ app()->getLocale() }}',
                        successUrl: '{{ route('checkout.success') }}',
                    },
                });

                // Paddle overlay kapandığında butonu sıfırla
                Paddle.Setup({
                    eventCallback: function(data) {
                        if (data.name === 'checkout.closed' || data.name === 'checkout.error') {
                            if (spinner) spinner.style.display = 'none';
                            btn.disabled = false;
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
