<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mailingList->landing_hero_title ?? $mailingList->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: {{ $mailingList->primary_color ?? '#be185d' }};
            --primary-hover: {{ $mailingList->secondary_color ?? '#9d174d' }};
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: #333;
            background: #f9fafb;
        }

        .hero {
            background: var(--primary-color);
            color: white;
            padding: 80px 0;
            min-height: 500px;
            display: flex;
            align-items: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.5rem;
            font-weight: 300;
            opacity: 0.95;
        }

        .hero-image {
            max-width: 100%;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .features {
            padding: 40px 0;
            background: white;
        }

        .feature-item {
            text-align: center;
            padding: 15px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        .feature-item h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .signup-section {
            padding: 40px 0;
            background: #f9fafb;
        }

        .signup-card {
            background: white;
            border-radius: 16px;
            padding: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 0 auto;
        }

        .signup-card h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            font-size: 1rem;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(190, 24, 93, 0.1);
        }

        .btn-signup {
            width: 100%;
            padding: 16px;
            font-size: 1.125rem;
            font-weight: 600;
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            color: white;
            transition: background 0.2s;
        }

        .btn-signup:hover {
            background: var(--primary-hover);
        }

        .success-message {
            background: #10b981;
            color: white;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero p {
                font-size: 1.25rem;
            }
            .signup-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1>{{ $mailingList->landing_hero_title ?? 'Velkommen' }}</h1>
                    <p>{{ $mailingList->landing_hero_subtitle ?? 'Tilmeld dig og få adgang til eksklusivt indhold' }}</p>
                </div>
                @if($mailingList->landing_hero_image)
                <div class="col-lg-6">
                    <img src="{{ asset('files/' . $mailingList->landing_hero_image) }}"
                         alt="Hero"
                         class="hero-image">
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Features Section -->
    @if($mailingList->landing_feature_1 || $mailingList->landing_feature_2 || $mailingList->landing_feature_3)
    <div class="features">
        <div class="container">
            <div class="row">
                @if($mailingList->landing_feature_1)
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h3>{{ $mailingList->landing_feature_1 }}</h3>
                    </div>
                </div>
                @endif

                @if($mailingList->landing_feature_2)
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <h3>{{ $mailingList->landing_feature_2 }}</h3>
                    </div>
                </div>
                @endif

                @if($mailingList->landing_feature_3)
                <div class="col-md-4">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <h3>{{ $mailingList->landing_feature_3 }}</h3>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Signup Section -->
    <div class="signup-section">
        <div class="container">
            <div class="signup-card">
                <h2>{{ $mailingList->landing_cta_text ?? 'Tilmeld nu' }}</h2>

                @if(session('success'))
                    <div class="success-message">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('landing.store', $mailingList->slug) }}" method="POST">
                    @csrf

                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="Dit navn"
                           required
                           value="{{ old('name') }}">

                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Din email"
                           required
                           value="{{ old('email') }}">

                    <button type="submit" class="btn-signup">
                        <i class="fa-solid fa-paper-plane me-2"></i>
                        {{ $mailingList->landing_cta_text ?? 'Tilmeld nu' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check for preview data in sessionStorage
        const previewData = sessionStorage.getItem('landing_preview_{{ $mailingList->id }}');
        if (previewData) {
            const data = JSON.parse(previewData);

            // Update hero title
            const titleEl = document.querySelector('.hero h1');
            if (titleEl && data.title) titleEl.textContent = data.title;

            // Update hero subtitle
            const subtitleEl = document.querySelector('.hero p');
            if (subtitleEl && data.subtitle) subtitleEl.textContent = data.subtitle;

            // Update features
            const featureEls = document.querySelectorAll('.feature-item h3');
            if (featureEls[0] && data.feature1) featureEls[0].textContent = data.feature1;
            if (featureEls[1] && data.feature2) featureEls[1].textContent = data.feature2;
            if (featureEls[2] && data.feature3) featureEls[2].textContent = data.feature3;

            // Update CTA text
            if (data.cta) {
                const ctaTitle = document.querySelector('.signup-card h2');
                if (ctaTitle) ctaTitle.textContent = data.cta;

                const ctaBtn = document.querySelector('.btn-signup');
                if (ctaBtn) ctaBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>' + data.cta;
            }
        }
    </script>
</body>
</html>
