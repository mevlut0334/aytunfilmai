<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('legal.contact_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #00D9FF;
            --accent:  #8338EC;
            --secondary: #FF006E;
        }
        body { background: #000; color: #fff; font-family: 'Segoe UI', sans-serif; }

        .legal-hero {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a0a2e 100%);
            border-bottom: 1px solid rgba(0,217,255,0.2);
            padding: 5rem 0 3rem;
            text-align: center;
        }
        .legal-hero h1 {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .legal-hero p { color: rgba(255,255,255,0.65); font-size: 1rem; margin-top: .5rem; }

        .contact-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(0,217,255,0.2);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            transition: all .3s;
        }
        .contact-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 30px rgba(0,217,255,0.2);
            transform: translateY(-4px);
        }
        .contact-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
        .contact-card h5 {
            color: var(--primary);
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: .4rem;
        }
        .contact-card a, .contact-card p {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 1.05rem;
            word-break: break-all;
        }
        .contact-card a:hover { color: var(--primary); }

        .btn-back {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(0,217,255,0.3);
            color: var(--primary);
            border-radius: 50px;
            padding: .6rem 1.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .3s;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }
        .btn-back:hover {
            background: rgba(0,217,255,0.1);
            color: #fff;
            border-color: var(--primary);
        }

        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: #25D366;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: .7rem 1.75rem;
            font-weight: 700;
            text-decoration: none;
            transition: all .3s;
            font-size: 1rem;
        }
        .btn-whatsapp:hover {
            background: #20BA5A;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37,211,102,.4);
        }

        footer.mini-footer {
            border-top: 1px solid rgba(0,217,255,0.15);
            padding: 2rem 0;
            text-align: center;
            color: rgba(255,255,255,0.4);
            font-size: .85rem;
        }
    </style>
</head>
<body>

    <!-- Hero -->
    <div class="legal-hero">
        <div class="container">
            <h1><i class="bi bi-envelope-heart"></i> {{ __('legal.contact_heading') }}</h1>
            <p>{{ __('legal.contact_intro') }}</p>
        </div>
    </div>

    <!-- Contact Cards -->
    <div class="container py-5">
        <div class="row g-4 justify-content-center mb-5">

            <!-- Telefon -->
            <div class="col-md-6 col-lg-4">
                <div class="contact-card text-center h-100">
                    <span class="contact-icon">📞</span>
                    <h5>{{ __('legal.contact_phone') }}</h5>
                    <a href="tel:+905314521253">{{ __('legal.contact_phone_value') }}</a>
                </div>
            </div>

            <!-- E-posta -->
            <div class="col-md-6 col-lg-4">
                <div class="contact-card text-center h-100">
                    <span class="contact-icon">✉️</span>
                    <h5>{{ __('legal.contact_email') }}</h5>
                    <a href="mailto:{{ __('legal.contact_email_value') }}">{{ __('legal.contact_email_value') }}</a>
                </div>
            </div>

            <!-- Adres -->
            <div class="col-md-6 col-lg-4">
                <div class="contact-card text-center h-100">
                    <span class="contact-icon">📍</span>
                    <h5>{{ __('legal.contact_address') }}</h5>
                    <p>{{ __('legal.contact_address_detail') }}</p>
                </div>
            </div>

            <!-- Çalışma Saatleri -->
            <div class="col-md-6 col-lg-4">
                <div class="contact-card text-center h-100">
                    <span class="contact-icon">🕐</span>
                    <h5>{{ __('legal.contact_hours') }}</h5>
                    <p>{{ __('legal.contact_hours_detail') }}</p>
                </div>
            </div>

            <!-- WhatsApp -->
            <div class="col-md-6 col-lg-4">
                <div class="contact-card text-center h-100 d-flex flex-column align-items-center justify-content-center">
                    <span class="contact-icon">💬</span>
                    <h5>{{ __('legal.contact_social') }}</h5>
                    @php
                        $wpNumber = \App\Models\SiteSetting::get('whatsapp_number', '+905314521253');
                        $wpLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $wpNumber);
                    @endphp
                    <a href="{{ $wpLink }}" target="_blank" class="btn-whatsapp mt-2">
                        <i class="bi bi-whatsapp"></i> {{ __('legal.contact_whatsapp') }}
                    </a>
                </div>
            </div>

        </div>

        <!-- Geri dön -->
        <div class="text-center mt-2 mb-5">
            <a href="{{ route('home') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> {{ __('legal.back_to_register') === 'Kayıt Sayfasına Dön' ? 'Ana Sayfaya Dön' : 'Back to Home' }}
            </a>
        </div>
    </div>

    <!-- Mini Footer (legal linkler) -->
    <footer class="mini-footer">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-center gap-4 mb-2" style="font-size:.9rem;">
                <a href="{{ route('legal.terms') }}" style="color:rgba(255,255,255,.5);text-decoration:none;">
                    <i class="bi bi-file-text"></i> {{ __('welcome.legal_terms') }}
                </a>
                <a href="{{ route('legal.copyright') }}" style="color:rgba(255,255,255,.5);text-decoration:none;">
                    <i class="bi bi-shield-check"></i> {{ __('welcome.legal_copyright') }}
                </a>
                <a href="{{ route('legal.kvkk') }}" style="color:rgba(255,255,255,.5);text-decoration:none;">
                    <i class="bi bi-lock"></i> {{ __('welcome.legal_kvkk') }}
                </a>
                <a href="{{ route('legal.personal-data') }}" style="color:rgba(255,255,255,.5);text-decoration:none;">
                    <i class="bi bi-person-lock"></i> {{ __('welcome.legal_personal') }}
                </a>
            </div>
            <p>&copy; {{ date('Y') }} Aytun Film AI</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
