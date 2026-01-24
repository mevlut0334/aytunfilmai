<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Token Paketleri - Aytun Film AI</title>

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

        /* Navbar - Profile ile aynı */
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
        .packages-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        /* Page Header */
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

        /* Package Cards */
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
            margin-bottom: 1.5rem;
        }

        .form-label {
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            color: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            color: white;
        }

        .form-select option {
            background: #1a1a1a;
            color: white;
        }

        .btn-add-cart {
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
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 0, 110, 0.5);
            color: white;
        }

        /* Info Card */
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

        /* Empty State */
        .empty-state {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: white;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Custom Toast Notification */
        .custom-toast {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9999;
            min-width: 350px;
            background: rgba(0, 0, 0, 0.95);
            border: 2px solid var(--primary);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.5);
            backdrop-filter: blur(10px);
            animation: slideInRight 0.4s ease-out;
        }

        .custom-toast.hide {
            animation: slideOutRight 0.4s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-bottom: none;
            border-radius: 13px 13px 0 0;
            padding: 1rem 1.5rem;
        }

        .toast-header strong {
            color: white;
            font-size: 1.1rem;
        }

        .toast-body {
            padding: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .toast-body .btn {
            margin-top: 1rem;
        }

        .btn-view-cart {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 20px rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
        }

        .btn-view-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 0, 110, 0.5);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .packages-container {
                padding: 5rem 1rem 2rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .page-header .lead {
                font-size: 1rem;
            }

            .package-card {
                padding: 1.5rem;
            }

            .token-icon {
                font-size: 3rem;
            }

            .token-amount {
                font-size: 2rem;
            }

            .package-price {
                font-size: 2rem;
            }

            .custom-toast {
                min-width: 300px;
                right: 10px;
                left: 10px;
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

                    @auth
                        <!-- Token Badge with Cart Icon -->
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
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Giriş
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
                    <i class="bi bi-coin"></i> Token Paketleri
                </h1>
                <p class="lead">
                    Film üretmek için ihtiyacınız olan token paketini seçin ve satın alın.
                </p>
            </div>

            @if($packages->isEmpty())
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="bi bi-info-circle"></i>
                    <h4>Şu anda aktif paket bulunmamaktadır.</h4>
                    <p>Lütfen daha sonra tekrar kontrol edin.</p>
                </div>
            @else
                <!-- Package Cards -->
                <div class="row g-4">
                    @foreach($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="package-card">
                                <!-- Package Name -->
                                <h3>{{ $package->name }}</h3>

                                <!-- Token Icon & Amount -->
                                <div class="text-center mb-3">
                                    <div class="token-icon">
                                        <i class="bi bi-coin"></i>
                                    </div>
                                    <h2 class="token-amount">
                                        {{ number_format($package->token_amount, 0) }}
                                    </h2>
                                    <p class="token-label">Token</p>
                                </div>

                                <!-- Description -->
                                @if($package->description)
                                    <p class="package-description">
                                        {{ $package->description }}
                                    </p>
                                @endif

                                <!-- Price -->
                                <div class="text-center mb-3">
                                    <h3 class="package-price">
                                        {{ number_format($package->price, 2) }} ₺
                                    </h3>
                                </div>

                                <!-- Add to Cart Form -->
                                <form action="{{ route('cart.add') }}" method="POST" class="mt-auto add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">

                                    <!-- Quantity -->
                                    <div class="mb-3">
                                        <label for="quantity_{{ $package->id }}" class="form-label">Miktar</label>
                                        <select name="quantity"
                                                id="quantity_{{ $package->id }}"
                                                class="form-select">
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button type="submit" class="btn btn-add-cart w-100">
                                        <i class="bi bi-cart-plus"></i> Sepete Ekle
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Info Card -->
                <div class="info-card">
                    <h5>
                        <i class="bi bi-info-circle-fill"></i> Token Nasıl Kullanılır?
                    </h5>
                    <ul>
                        <li>Satın aldığınız tokenlar hesabınıza otomatik olarak yüklenir.</li>
                        <li>Her film talebi için belirli miktarda token harcanır.</li>
                        <li>Token bakiyenizi profil sayfanızdan kontrol edebilirsiniz.</li>
                        <li>Tokenlar süresiz olarak hesabınızda kalır.</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Toast Container -->
    <div id="toastContainer"></div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript for Cart Notification -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kullanıcı giriş kontrolü
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

            // Tüm sepete ekle formlarını dinle
            const forms = document.querySelectorAll('.add-to-cart-form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Giriş yapmamış kullanıcıyı login sayfasına yönlendir
                    if (!isAuthenticated) {
                        window.location.href = "{{ route('login') }}";
                        return;
                    }

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showCartNotification(data.message || 'Ürün sepete eklendi!');
                        } else {
                            alert(data.message || 'Bir hata oluştu!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                    });
                });
            });
        });

        function showCartNotification(message) {
            const toastContainer = document.getElementById('toastContainer');

            const toastHTML = `
                <div class="custom-toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                        <strong class="me-auto">Başarılı!</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <p class="mb-0"><i class="bi bi-cart-check"></i> ${message}</p>
                        <a href="{{ route('cart.index') }}" class="btn btn-view-cart w-100">
                            <i class="bi bi-cart3"></i> Sepeti Görüntüle
                        </a>
                    </div>
                </div>
            `;

            toastContainer.innerHTML = toastHTML;
            const toastElement = toastContainer.querySelector('.custom-toast');

            // Close button functionality
            const closeBtn = toastElement.querySelector('.btn-close');
            closeBtn.addEventListener('click', function() {
                toastElement.classList.add('hide');
                setTimeout(() => {
                    toastElement.remove();
                }, 400);
            });

            // Auto hide after 10 seconds
            setTimeout(() => {
                if (toastElement) {
                    toastElement.classList.add('hide');
                    setTimeout(() => {
                        toastElement.remove();
                    }, 400);
                }
            }, 10000);
        }
    </script>
</body>
</html>
