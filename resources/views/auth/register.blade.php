<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.register_title') }}</title>

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

        .register-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.1);
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 2rem;
            text-align: center;
        }

        .register-header h4 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .register-body {
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

        .form-text {
            color: rgba(255, 255, 255, 0.6);
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

        .form-check-label a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s;
        }

        .form-check-label a:hover {
            color: var(--secondary);
            text-shadow: 0 0 10px rgba(255, 0, 110, 0.5);
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255, 0, 110, 0.5);
            color: white;
        }

        .login-link {
            color: rgba(255, 255, 255, 0.7);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .login-link a:hover {
            color: var(--secondary);
        }

        hr {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 2rem 0;
        }

        .section-title {
            color: white;
            font-weight: bold;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .invalid-feedback {
            color: var(--secondary);
            font-weight: 500;
        }

        .is-invalid {
            border-color: var(--secondary) !important;
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 5rem 1rem 2rem;
            }

            .register-body {
                padding: 1.5rem;
            }

            .register-header h4 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-film"></i> {{ __('auth.brand') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('auth.packages') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> {{ __('auth.login_nav') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="register-card">
                        <div class="register-header">
                            <h4><i class="bi bi-person-plus"></i> {{ __('auth.register_heading') }}</h4>
                        </div>
                        <div class="register-body">
                            <form action="{{ route('register.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('auth.name') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('auth.email') }} <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('auth.phone') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="{{ __('auth.phone_placeholder') }}"
                                           required>
                                    <small class="form-text">{{ __('auth.phone_hint') }}</small>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('auth.password') }} <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           required>
                                    <small class="form-text">{{ __('auth.password_hint') }}</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">{{ __('auth.password_confirmation') }} <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required>
                                </div>

                                <hr>

                                <h5 class="section-title">{{ __('auth.approvals_title') }} <span class="text-danger">*</span></h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                           type="checkbox"
                                           name="terms_accepted"
                                           id="terms_accepted"
                                           value="1"
                                           {{ old('terms_accepted') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="terms_accepted">
                                        <a href="{{ route('legal.terms') }}" target="_blank">{{ __('auth.terms_link') }}</a>{{ __('auth.terms_label') }} <span class="text-danger">*</span>
                                    </label>
                                    @error('terms_accepted')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('copyright_accepted') is-invalid @enderror"
                                           type="checkbox"
                                           name="copyright_accepted"
                                           id="copyright_accepted"
                                           value="1"
                                           {{ old('copyright_accepted') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="copyright_accepted">
                                        <a href="{{ route('legal.copyright') }}" target="_blank">{{ __('auth.copyright_link') }}</a>{{ __('auth.terms_label') }} <span class="text-danger">*</span>
                                    </label>
                                    @error('copyright_accepted')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('kvkk_accepted') is-invalid @enderror"
                                           type="checkbox"
                                           name="kvkk_accepted"
                                           id="kvkk_accepted"
                                           value="1"
                                           {{ old('kvkk_accepted') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="kvkk_accepted">
                                        <a href="{{ route('legal.kvkk') }}" target="_blank">{{ __('auth.kvkk_link') }}</a>{{ __('auth.terms_label') }} <span class="text-danger">*</span>
                                    </label>
                                    @error('kvkk_accepted')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input @error('personal_data_accepted') is-invalid @enderror"
                                           type="checkbox"
                                           name="personal_data_accepted"
                                           id="personal_data_accepted"
                                           value="1"
                                           {{ old('personal_data_accepted') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="personal_data_accepted">
                                        <a href="{{ route('legal.personal-data') }}" target="_blank">{{ __('auth.personal_data_link') }}</a>{{ __('auth.terms_label') }} <span class="text-danger">*</span>
                                    </label>
                                    @error('personal_data_accepted')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-register">
                                        <i class="bi bi-person-plus"></i> {{ __('auth.register_button') }}
                                    </button>
                                </div>

                                <div class="text-center mt-3 login-link">
                                    <p class="mb-0">{{ __('auth.already_account') }} <a href="{{ route('login') }}">{{ __('auth.login_link') }}</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
