<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş Yap - Aytun Film AI</title>

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

        /* Navbar - Welcome ile aynı */
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

        /* Content */
        .login-container {
            padding: 8rem 0 4rem;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.1);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 2rem;
            text-align: center;
        }

        .login-header h4 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .login-body {
            padding: 2.5rem;
        }

        .form-label {
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            color: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-check-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            width: 1.25rem;
            height: 1.25rem;
        }

        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }

        .form-check-label {
            color: rgba(255, 255, 255, 0.9);
            margin-left: 0.5rem;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 1rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 5px 20px rgba(255, 0, 110, 0.3);
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 0, 110, 0.5);
            color: white;
        }

        .register-link {
            color: rgba(255, 255, 255, 0.7);
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .register-link a:hover {
            color: var(--secondary);
        }

        .invalid-feedback {
            color: var(--secondary);
            font-weight: 500;
        }

        .is-invalid {
            border-color: var(--secondary) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                padding: 5rem 1rem 2rem;
            }

            .login-body {
                padding: 1.5rem;
            }

            .login-header h4 {
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> Paketler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-neon" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i> Kayıt Ol
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Content -->
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-card">
                        <div class="login-header">
                            <h4><i class="bi bi-box-arrow-in-right"></i> Giriş Yap</h4>
                        </div>
                        <div class="login-body">
                            <form action="{{ route('login.store') }}" method="POST">
                                @csrf

                                <!-- E-posta -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Şifre -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember Me -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="remember"
                                           id="remember"
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Beni Hatırla
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-login">
                                        <i class="bi bi-box-arrow-in-right"></i> Giriş Yap
                                    </button>
                                </div>

                                <div class="text-center mt-3 register-link">
                                    <p class="mb-0">
                                        Hesabınız yok mu? <a href="{{ route('register') }}">Kayıt Olun</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
