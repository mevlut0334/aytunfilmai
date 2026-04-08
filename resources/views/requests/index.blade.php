<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('requests.index_title') }}</title>

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
            --success: #10b981;
            --warning: #fbbf24;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-dark);
            color: white;
            min-height: 100vh;
        }

        /* Navbar & Utility */
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
            text-decoration: none;
            transition: all 0.3s;
        }

        .token-badge:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 0 30px rgba(0, 217, 255, 0.5);
        }

        /* Requests Container */
        .requests-container {
            padding: 7rem 0 4rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-pending { background: linear-gradient(135deg, var(--warning) 0%, #f59e0b 100%); }
        .bg-processing { background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); }
        .bg-completed { background: linear-gradient(135deg, var(--success) 0%, #059669 100%); }
        .bg-failed { background: linear-gradient(135deg, var(--secondary) 0%, #dc3545 100%); }

        /* Request Cards */
        .request-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.15);
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
        }

        .request-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 12px 30px rgba(0, 217, 255, 0.2);
        }

        .card-img-container {
            height: 220px;
            overflow: hidden;
            position: relative;
            background: #111;
        }

        .request-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .request-card:hover img {
            transform: scale(1.05);
        }

        .placeholder-icon {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, #0a0a0a, #1a1a1a);
        }

        .placeholder-icon i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.1);
        }

        .card-body-custom {
            padding: 1.5rem;
        }

        .btn-action {
            border-radius: 12px;
            padding: 0.6rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-create-request {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 217, 255, 0.3);
        }

        .empty-state {
            background: rgba(255, 255, 255, 0.03);
            border: 2px dashed rgba(0, 217, 255, 0.2);
            border-radius: 30px;
            padding: 5rem 2rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-film"></i> {{ __('requests.brand') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('packages.index') }}">{{ __('requests.packages') }}</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('requests.index') }}">{{ __('requests.my_requests') }}</a></li>
                    <li class="nav-item">
                        <span class="token-badge mx-lg-3">
                           <i class="bi bi-coin"></i>
                               <span>{{ number_format(auth()->user()->token_balance ?? 0, 0) }}</span>
                                   </span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person"></i> {{ __('requests.my_profile') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag"></i> {{ __('requests.my_orders') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> {{ __('requests.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="requests-container">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div>
                    <h2 class="fw-bold mb-0 text-white"><i class="bi bi-collection-play me-2 text-info"></i> {{ __('requests.index_heading') }}</h2>
                    <p class="text-white-50 mt-2 mb-0">{{ __('requests.index_subtitle') }}</p>
                </div>
                <a href="{{ route('requests.create') }}" class="btn btn-create-request">
                    <i class="bi bi-plus-lg me-2"></i> {{ __('requests.new_film') }}
                </a>
            </div>

            @forelse($requests as $request)
                @if($loop->first) <div class="row g-4"> @endif

                <div class="col-md-6 col-lg-4">
                    <div class="request-card">
                        <div class="position-absolute top-0 end-0 m-3" style="z-index: 5;">
                            @php
                                $statusClasses = [
                                    'pending'    => 'bg-pending',
                                    'processing' => 'bg-processing',
                                    'completed'  => 'bg-completed',
                                    'failed'     => 'bg-failed'
                                ];
                            @endphp
                            <span class="status-badge {{ $statusClasses[$request->status] ?? 'bg-secondary' }}">
                                {{ __('requests.status_' . $request->status) }}
                            </span>
                        </div>

                        <div class="card-img-container">
                            @if($request->characters->isNotEmpty() && ($char = $request->characters->first()) && $char->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $char->images->first()->image_path) }}" alt="{{ $request->title }}" loading="lazy">
                            @else
                                <div class="placeholder-icon">
                                    <i class="bi bi-film"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body-custom">
                            <h5 class="fw-bold text-white mb-2 text-truncate">{{ $request->title }}</h5>
                            <p class="text-white-50 small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $request->description }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-4 text-white-50 small">
                                <span><i class="bi bi-calendar3 me-1 text-info"></i> {{ $request->created_at->translatedFormat('d M Y') }}</span>
                                <span><i class="bi bi-people me-1 text-info"></i> {{ $request->characters->count() }} {{ __('requests.character_count') }}</span>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('requests.show', $request->id) }}" class="btn btn-primary btn-action">
                                    <i class="bi bi-play-circle me-1"></i> {{ __('requests.view_details') }}
                                </a>

                                @if(in_array($request->status, ['pending', 'failed']))
                                    <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="d-grid">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-action"
                                                onclick="return confirm('{{ __('requests.delete_confirm') }}')">
                                            <i class="bi bi-trash3 me-1"></i> {{ __('requests.delete_request') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($loop->last) </div> @endif
            @empty
                <div class="empty-state">
                    <div class="mb-4">
                        <i class="bi bi-camera-reels display-1 text-white-50"></i>
                    </div>
                    <h3 class="fw-bold">{{ __('requests.empty_title') }}</h3>
                    <p class="text-white-50 mx-auto mb-4" style="max-width: 400px;">
                        {{ __('requests.empty_text') }}
                    </p>
                    <a href="{{ route('requests.create') }}" class="btn btn-create-request px-5">
                        <i class="bi bi-plus-lg me-2"></i> {{ __('requests.first_request') }}
                    </a>
                </div>
            @endforelse
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
