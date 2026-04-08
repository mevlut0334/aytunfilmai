<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('packages.success_title') }} - Aytun Film AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #00D9FF;
            --accent:  #8338EC;
            --bg-dark: #000000;
        }
        body {
            background: var(--bg-dark);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 1rem;
        }
        .result-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(16,185,129,0.4);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            text-align: center;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 0 60px rgba(16,185,129,0.15);
        }
        .icon-success {
            font-size: 5rem;
            color: #10b981;
            text-shadow: 0 0 30px rgba(16,185,129,0.5);
        }
        h1 { color: #10b981; font-weight: bold; margin: 1rem 0 0.5rem; }
        .lead { color: rgba(255,255,255,0.65); margin-bottom: 0; }
        .info-box {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.85rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            margin: 0.4rem;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(0,217,255,0.4);
            color: white;
        }
        .btn-outline-custom {
            background: transparent;
            border: 1px solid rgba(0,217,255,0.4);
            color: var(--primary);
            padding: 0.85rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            margin: 0.4rem;
        }
        .btn-outline-custom:hover {
            background: rgba(0,217,255,0.1);
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="result-card">
        <div class="icon-success">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1>{{ __('packages.success_title') }}</h1>
        <p class="lead">{{ __('packages.success_sub') }}</p>

        <div class="info-box">
            <i class="bi bi-coin text-warning me-1"></i>
            {{ __('packages.success_processing') }}
        </div>

        <div class="mt-3">
            <a href="{{ route('user.profile') }}" class="btn-primary-custom">
                <i class="bi bi-person-circle me-1"></i> {{ __('packages.success_profile') }}
            </a>
            <a href="{{ route('home') }}" class="btn-outline-custom">
                <i class="bi bi-house-door me-1"></i> {{ __('packages.success_go_home') }}
            </a>
        </div>
    </div>
</body>
</html>
