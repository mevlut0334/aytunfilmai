<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('requests.create_title') }}</title>

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

        .token-badge i { font-size: 1.2rem; }

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

        .request-container {
            padding: 6rem 0 4rem;
            min-height: 100vh;
        }

        .page-header { margin-bottom: 2rem; }
        .page-header h2 { font-size: 2rem; font-weight: bold; color: white; }
        .page-header p { color: rgba(255, 255, 255, 0.7); }

        .card-custom {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header-custom h5 { margin: 0; font-weight: bold; font-size: 1.2rem; }

        .card-header-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        }

        .card-body-custom { padding: 1.5rem; }

        .alert-custom {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 15px;
            color: white;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-custom i { color: var(--primary); }

        .form-label { color: white; font-weight: 500; margin-bottom: 0.5rem; }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            color: white;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .form-select option { background-color: var(--bg-medium); color: white; }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(0, 217, 255, 0.3);
            color: white;
        }

        .form-control::placeholder { color: rgba(255, 255, 255, 0.5); }
        textarea.form-control { min-height: 150px; }
        .form-text { color: rgba(255, 255, 255, 0.6); }

        .character-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(0, 217, 255, 0.2) !important;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .character-item h6 { color: white; font-weight: bold; }

        .btn-add-character {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .btn-add-character:hover { background: rgba(255, 255, 255, 0.2); color: white; }

        .btn-remove {
            background: linear-gradient(135deg, var(--secondary) 0%, #dc3545 100%);
            border: none;
            color: white;
            border-radius: 10px;
        }

        .btn-remove:hover { opacity: 0.8; color: white; }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 5px 20px rgba(0, 217, 255, 0.3);
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 217, 255, 0.5);
            color: white;
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .btn-cancel:hover { background: rgba(255, 255, 255, 0.2); color: white; }

        .info-card {
            background: rgba(0, 217, 255, 0.1);
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-card h6 { color: var(--primary); font-weight: bold; margin-bottom: 1rem; }
        .info-card ul { color: rgba(255, 255, 255, 0.8); margin: 0; }
        .info-card ul li { margin-bottom: 0.5rem; }

        .invalid-feedback { color: var(--secondary); font-weight: 500; }
        .is-invalid { border-color: var(--secondary) !important; }

        @media (max-width: 768px) {
            .request-container { padding: 5rem 1rem 2rem; }
            .page-header h2 { font-size: 1.5rem; }
            .btn-submit, .btn-cancel { width: 100%; margin-bottom: 0.5rem; }
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('packages.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('requests.packages') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('requests.index') }}">
                            <i class="bi bi-film"></i> {{ __('requests.my_requests') }}
                        </a>
                    </li>
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
                                    <i class="bi bi-person"></i> {{ __('requests.my_profile') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('requests.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="request-container">
        <div class="container">
            <div class="page-header">
                <h2><i class="bi bi-film"></i> {{ __('requests.create_heading') }}</h2>
                <p>{{ __('requests.create_subtitle') }}</p>
            </div>

            <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data" id="requestForm">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card-custom">
                            <div class="card-header-custom">
                                <h5><i class="bi bi-people"></i> {{ __('requests.characters_heading') }}</h5>
                                <button type="button" class="btn btn-sm btn-add-character" id="addCharacterBtn">
                                    <i class="bi bi-plus-circle"></i> {{ __('requests.add_character') }}
                                </button>
                            </div>
                            <div class="card-body-custom">
                                <div class="alert-custom">
                                    <i class="bi bi-info-circle-fill"></i>
                                    {!! __('requests.character_alert') !!}
                                </div>
                                <div id="charactersList"></div>
                            </div>
                        </div>

                        <div class="card-custom">
                            <div class="card-header-custom card-header-primary">
                                <h5><i class="bi bi-info-circle"></i> {{ __('requests.film_info') }}</h5>
                            </div>
                            <div class="card-body-custom">
                                <div class="mb-3">
                                    <label for="video_format" class="form-label">{{ __('requests.video_format') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('video_format') is-invalid @enderror"
                                            id="video_format" name="video_format" required>
                                        <option value="horizontal" {{ old('video_format') == 'horizontal' ? 'selected' : '' }}>
                                            {{ __('requests.format_horizontal') }}
                                        </option>
                                        <option value="vertical" {{ old('video_format') == 'vertical' ? 'selected' : '' }}>
                                            {{ __('requests.format_vertical') }}
                                        </option>
                                    </select>
                                    @error('video_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('requests.film_title') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title"
                                           value="{{ old('title') }}"
                                           maxlength="200" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="description" class="form-label">{{ __('requests.film_description') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description"
                                              rows="6" required>{{ old('description') }}</textarea>
                                    <small class="form-text">{{ __('requests.description_hint') }}</small>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="info-card">
                            <h6><i class="bi bi-lightbulb-fill"></i> {{ __('requests.tips_title') }}</h6>
                            <ul class="small">
                                <li>{!! __('requests.tip_format') !!}</li>
                                <li>{{ __('requests.tip_title') }}</li>
                                <li>{{ __('requests.tip_desc') }}</li>
                                <li>{{ __('requests.tip_char') }}</li>
                                <li>{{ __('requests.tip_img') }}</li>
                                <li>{{ __('requests.tip_format_img') }}</li>
                            </ul>
                        </div>

                        <div class="info-card">
                            <h6><i class="bi bi-camera-fill"></i> {{ __('requests.photo_tips_title') }}</h6>
                            <ul class="small">
                                <li>{{ __('requests.photo_tip_1') }}</li>
                                <li>{{ __('requests.photo_tip_2') }}</li>
                                <li>{{ __('requests.photo_tip_3') }}</li>
                                <li>{{ __('requests.photo_tip_4') }}</li>
                                <li>{{ __('requests.photo_tip_5') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send"></i> {{ __('requests.submit') }}
                        </button>
                        <a href="{{ route('requests.index') }}" class="btn btn-cancel">
                            <i class="bi bi-x"></i> {{ __('requests.cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    let characterCount = 0;
    const maxCharacters = 5;

    const lang = {
        character_label  : '{{ __('requests.character_label') }}',
        character_name   : '{{ __('requests.character_name') }}',
        character_images : '{{ __('requests.character_images') }}',
        character_img_hint: '{{ __('requests.character_img_hint') }}',
        character_img_text: '{{ __('requests.character_img_text') }}',
        delete_character : '{{ __('requests.delete_character') }}',
        max_char_alert   : '{{ __('requests.max_char_alert') }}',
        min_img_alert    : '{{ __('requests.min_img_alert') }}',
        max_img_alert    : '{{ __('requests.max_img_alert') }}',
    };

    document.getElementById('addCharacterBtn').addEventListener('click', function () {
        if (characterCount >= maxCharacters) {
            alert(lang.max_char_alert);
            return;
        }
        characterCount++;
        addCharacter(characterCount);
    });

    function addCharacter(index) {
        const characterHtml = `
            <div class="character-item" data-character="${index}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="bi bi-person-fill"></i> ${lang.character_label} ${index}</h6>
                    <button type="button" class="btn btn-sm btn-remove" onclick="removeCharacter(${index})">
                        <i class="bi bi-trash"></i> ${lang.delete_character}
                    </button>
                </div>

                <div class="mb-3">
                    <label for="character_name_${index}" class="form-label">
                        ${lang.character_name} <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control"
                           id="character_name_${index}"
                           name="characters[${index - 1}][name]"
                           maxlength="100"
                           required>
                </div>

                <div class="mb-0">
                    <label for="character_images_${index}" class="form-label">
                        ${lang.character_images} <span class="text-danger">*</span>
                        <small class="text-muted">${lang.character_img_hint}</small>
                    </label>
                    <input type="file"
                           class="form-control"
                           id="character_images_${index}"
                           name="characters[${index - 1}][images][]"
                           accept="image/jpeg,image/jpg,image/png"
                           multiple
                           required>
                    <small class="form-text">${lang.character_img_text}</small>
                </div>
            </div>
        `;
        document.getElementById('charactersList').insertAdjacentHTML('beforeend', characterHtml);
    }

    function removeCharacter(index) {
        const item = document.querySelector(`[data-character="${index}"]`);
        if (item) { item.remove(); characterCount--; }
    }

    document.getElementById('requestForm').addEventListener('submit', function (e) {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        for (let input of fileInputs) {
            if (input.files.length > 0 && input.files.length < 5) {
                e.preventDefault();
                alert(lang.min_img_alert);
                return false;
            }
            if (input.files.length > 10) {
                e.preventDefault();
                alert(lang.max_img_alert);
                return false;
            }
        }
    });
    </script>
</body>
</html>
