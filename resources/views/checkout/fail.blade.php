<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('packages.fail_title') }} - Aytun Film AI</title>
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
        }
        .result-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(239,68,68,0.4);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            text-align: center;
            max-width: 520px;
            width: 100%;
            box-shadow: 0 0 60px rgba(239,68,68,0.1);
        }
        .icon-fail {
            font-size: 5rem;
            color: #ef4444;
            text-shadow: 0 0 30px rgba(239,68,68,0.4);
        }
        h1 { color: #ef4444; font-weight: bold; margin: 1rem 0 0.5rem; }
        .lead { color: rgba(255,255,255,0.65); }
        .support-box {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
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
    </style>
</head>
<body>
    <div class="result-card">
        <div class="icon-fail">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <h1>{{ __('packages.fail_title') }}</h1>
        <p class="lead">{{ __('packages.fail_sub') }}</p>

        <div class="support-box">
            <i class="bi bi-headset me-2"></i>
            {{ __('packages.fail_support') }}
            <br>
            <strong>destek@aytunfilm.ai</strong>
        </div>

        <div class="mt-2">
            <a href="{{ route('packages.index') }}" class="btn-primary-custom">
                <i class="bi bi-arrow-clockwise me-1"></i> {{ __('packages.fail_retry') }}
            </a>
        </div>
    </div>
</body>
</html>
