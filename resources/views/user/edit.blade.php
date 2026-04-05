<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('user.edit_title') }}</title>

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
            min-height: 100vh;
        }

        .navbar-custom {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 217, 255, 0.2);
            padding: 1rem 0;
        }

        .navbar-custom .navbar-brand {
            font-size: 1.5rem; font-weight: bold;
            color: var(--primary);
            text-shadow: 0 0 20px rgba(0, 217, 255, 0.5);
        }

        .navbar-custom .nav-link { color: white; margin: 0 1rem; transition: all 0.3s; }
        .navbar-custom .nav-link:hover { color: var(--primary); text-shadow: 0 0 10px rgba(0, 217, 255, 0.5); }

        .token-badge {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: bold;
            color: white; display: inline-flex; align-items: center; gap: 0.5rem;
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3); margin-right: 1rem;
            text-decoration: none; transition: all 0.3s;
        }

        .token-badge:hover { transform: translateY(-2px); box-shadow: 0 0 30px rgba(0, 217, 255, 0.5); color: white; }
        .token-badge i { font-size: 1.2rem; }

        .dropdown-menu {
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 15px; padding: 0.5rem; backdrop-filter: blur(10px);
        }

        .dropdown-item { color: white; border-radius: 10px; padding: 0.75rem 1rem; transition: all 0.3s; }
        .dropdown-item:hover { background: rgba(0, 217, 255, 0.2); color: var(--primary); }
        .dropdown-item i { margin-right: 0.5rem; width: 20px; }

        .edit-container { padding: 6rem 0 4rem; min-height: 100vh; }

        .edit-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.1);
        }

        .edit-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            padding: 2rem; text-align: center;
        }

        .edit-header h4 { margin: 0; font-size: 1.8rem; font-weight: bold; }
        .edit-body { padding: 2.5rem; }

        .form-label { color: white; font-weight: 500; margin-bottom: 0.5rem; }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            color: white; border-radius: 10px; padding: 0.75rem 1rem; transition: all 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3); color: white;
        }

        .form-control::placeholder { color: rgba(255, 255, 255, 0.5); }
        .form-text { color: rgba(255, 255, 255, 0.6); }
        hr { border-color: rgba(255, 255, 255, 0.1); margin: 2rem 0; }

        .section-title { color: white; font-weight: bold; margin-bottom: 1rem; font-size: 1.3rem; }
        .section-note { color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; margin-bottom: 1.5rem; }

        .btn-update {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none; color: white; padding: 0.75rem 2rem;
            border-radius: 50px; font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3); transition: all 0.3s;
        }

        .btn-update:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 217, 255, 0.5); color: white; }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white; padding: 0.75rem 2rem; border-radius: 50px; font-weight: bold; transition: all 0.3s;
        }

        .btn-cancel:hover { background: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.5); color: white; }

        .invalid-feedback { color: var(--secondary); font-weight: 500; }
        .is-invalid { border-color: var(--secondary) !important; }

        @media (max-width: 768px) {
            .edit-container { padding: 5rem 1rem 2rem; }
            .edit-body { padding: 1.5rem; }
            .edit-header h4 { font-size: 1.5rem; }
            .btn-update, .btn-cancel { width: 100%; margin-bottom: 0.5rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-film"></i> {{ __('user.brand') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('user.packages') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('requests.index') }}">
                            <i class="bi bi-film"></i> {{ __('user.my_requests') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="token-badge">
                            <i class="bi bi-coin"></i>
                            <span>{{ number_format($user->token_balance, 0) }}</span>
                            <i class="bi bi-cart3"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ $user->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="bi bi-person"></i> {{ __('user.my_profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag"></i> {{ __('user.my_orders') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('user.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="edit-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="edit-card">
                        <div class="edit-header">
                            <h4><i class="bi bi-pencil"></i> {{ __('user.edit_heading') }}</h4>
                        </div>
                        <div class="edit-body">
                            <form action="{{ route('user.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('user.full_name') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('user.email') }} <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('user.phone') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="{{ __('user.phone_placeholder') }}" required>
                                    <small class="form-text">{{ __('user.phone_hint') }}</small>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <h5 class="section-title">{{ __('user.change_password') }}</h5>
                                <p class="section-note">{{ __('user.change_password_note') }}</p>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('user.new_password') }}</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    <small class="form-text">{{ __('user.password_hint') }}</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">{{ __('user.new_password_confirm') }}</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation">
                                </div>

                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <a href="{{ route('user.profile') }}" class="btn btn-cancel">
                                        <i class="bi bi-x-circle"></i> {{ __('user.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-update">
                                        <i class="bi bi-check-circle"></i> {{ __('user.update') }}
                                    </button>
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
