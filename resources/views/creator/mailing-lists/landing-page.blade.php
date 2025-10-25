<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Grupper</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Landing page</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                Gruppe: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
            </h1>
        </div>

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-circle-user me-1"></i> Medlemmer
                </a>
            </li>
            <li class="nav-item dropdown" role="presentation">
                <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-plus me-1"></i> Sign up
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                        <i class="fa-solid fa-code me-2"></i> Forms
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                        <i class="fa-solid fa-qrcode me-2"></i> QR Code
                    </a></li>
                    <li><a class="dropdown-item active" href="{{ route('creator.mailing-lists.landing-page', $mailingList) }}">
                        <i class="fa-solid fa-image me-2"></i> Landing page
                    </a></li>
                </ul>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.welcome', $mailingList) }}">
                    <i class="fa-solid fa-heart me-1"></i> Velkomst
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.content', $mailingList) }}">
                    <i class="fa-solid fa-circle-play me-1"></i> Indhold
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.settings', $mailingList) }}">
                    <i class="fa-solid fa-gear me-1"></i> Indstillinger
                </a>
            </li>
        </ul>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Description and Links -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 style="font-size: 20px; font-weight: 600; color: #333;">
                <i class="fa-solid fa-image me-2" style="color: var(--primary-color);"></i>
                Landing page til gruppen
            </h2>
            <div class="d-flex gap-2 align-items-center">
                <div class="input-group" style="max-width: 600px;">
                    <input type="text"
                           class="form-control"
                           id="landingPageUrl"
                           value="{{ url('/landing/' . $mailingList->slug) }}"
                           readonly
                           style="font-family: monospace; font-size: 12px;">
                    <button class="btn btn-outline-primary" type="button" onclick="copyLandingUrl(event)">
                        <i class="fa-solid fa-copy me-1"></i> Kopier link
                    </button>
                </div>
                <a href="{{ route('landing.show', $mailingList->slug) }}" target="_blank" class="btn btn-primary" style="white-space: nowrap;">
                    <i class="fa-solid fa-eye me-1"></i> Besøg siden
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Editor Column -->
            <div class="col-lg-5">
                <form action="{{ route('creator.mailing-lists.update-landing-page', $mailingList) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="card mb-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                Header
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="landing_hero_title" class="form-label" style="font-weight: 500;">Titel</label>
                                <input type="text"
                                       class="form-control"
                                       id="landing_hero_title"
                                       name="landing_hero_title"
                                       value="{{ old('landing_hero_title', $mailingList->landing_hero_title) }}"
                                       placeholder="F.eks. Velkommen til vores community">
                            </div>

                            <div class="mb-3">
                                <label for="landing_hero_subtitle" class="form-label" style="font-weight: 500;">Undertitel</label>
                                <input type="text"
                                       class="form-control"
                                       id="landing_hero_subtitle"
                                       name="landing_hero_subtitle"
                                       value="{{ old('landing_hero_subtitle', $mailingList->landing_hero_subtitle) }}"
                                       placeholder="F.eks. Få adgang til eksklusivt indhold">
                            </div>

                            <div class="mb-0">
                                <label for="landing_hero_image" class="form-label" style="font-weight: 500;">Billede</label>
                                @if($mailingList->landing_hero_image)
                                    <div class="mb-3">
                                        <img src="{{ asset('files/' . $mailingList->landing_hero_image) }}"
                                             style="max-width: 100%; border-radius: 4px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                                <i class="fa-solid fa-trash me-1"></i> Fjern billede
                                            </button>
                                        </div>
                                        <input type="hidden" id="remove_image" name="remove_image" value="0">
                                    </div>
                                @endif
                                <div class="upload-zone" id="upload-zone" onclick="document.getElementById('landing_hero_image').click()">
                                    <input type="file"
                                           class="d-none"
                                           id="landing_hero_image"
                                           name="landing_hero_image"
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                    <div class="text-center py-4">
                                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        <p class="mb-1 mt-3" style="font-weight: 500; color: #64748b;">
                                            @if($mailingList->landing_hero_image)
                                                Klik for at uploade nyt billede
                                            @else
                                                Klik for at uploade billede
                                            @endif
                                        </p>
                                        <p class="small mb-0" style="color: #94a3b8;">eller træk og slip en fil her</p>
                                        <p class="small text-muted mt-2">Max 2MB. JPG, PNG eller WebP</p>
                                    </div>
                                    <div id="image-preview" class="mt-3" style="display: none;">
                                        <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-list" style="color: var(--primary-color);"></i> Features (3 stk)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="landing_feature_1" class="form-label" style="font-weight: 500;">Feature 1</label>
                                <input type="text"
                                       class="form-control"
                                       id="landing_feature_1"
                                       name="landing_feature_1"
                                       value="{{ old('landing_feature_1', $mailingList->landing_feature_1) }}"
                                       placeholder="F.eks. Ugentlige webinarer">
                            </div>

                            <div class="mb-3">
                                <label for="landing_feature_2" class="form-label" style="font-weight: 500;">Feature 2</label>
                                <input type="text"
                                       class="form-control"
                                       id="landing_feature_2"
                                       name="landing_feature_2"
                                       value="{{ old('landing_feature_2', $mailingList->landing_feature_2) }}"
                                       placeholder="F.eks. Eksklusivt indhold">
                            </div>

                            <div class="mb-0">
                                <label for="landing_feature_3" class="form-label" style="font-weight: 500;">Feature 3</label>
                                <input type="text"
                                       class="form-control"
                                       id="landing_feature_3"
                                       name="landing_feature_3"
                                       value="{{ old('landing_feature_3', $mailingList->landing_feature_3) }}"
                                       placeholder="F.eks. Community support">
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-hand-pointer" style="color: var(--primary-color);"></i> Call-to-action
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-0">
                                <label for="landing_cta_text" class="form-label" style="font-weight: 500;">Knap tekst</label>
                                <input type="text"
                                       class="form-control"
                                       id="landing_cta_text"
                                       name="landing_cta_text"
                                       value="{{ old('landing_cta_text', $mailingList->landing_cta_text ?? 'Tilmeld nu') }}"
                                       placeholder="Tilmeld nu">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fa-solid fa-save me-1"></i> Gem landing page
                    </button>
                </form>
            </div>

            <!-- Preview Column -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-eye" style="color: var(--primary-color);"></i> Preview
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <iframe id="preview-iframe"
                                src="{{ route('landing.show', $mailingList->slug) }}"
                                style="width: 100%; height: 800px; border: none;">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: none;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            font-size: 14px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .form-control {
            border-radius: 6px;
        }
        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        .upload-zone:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
        }

        /* Dropdown on hover */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin-top: 0;
        }
    </style>

    <script>
        // Real-time preview update
        const iframe = document.getElementById('preview-iframe');
        let updateTimeout;

        // Store form values in sessionStorage and update preview
        function updatePreview() {
            const formData = {
                title: document.getElementById('landing_hero_title').value,
                subtitle: document.getElementById('landing_hero_subtitle').value,
                feature1: document.getElementById('landing_feature_1').value,
                feature2: document.getElementById('landing_feature_2').value,
                feature3: document.getElementById('landing_feature_3').value,
                cta: document.getElementById('landing_cta_text').value,
            };

            // Store in sessionStorage for the landing page to read
            sessionStorage.setItem('landing_preview_{{ $mailingList->id }}', JSON.stringify(formData));

            // Reload iframe
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(() => {
                iframe.src = iframe.src;
            }, 300);
        }

        // Listen to all text inputs (except the URL field)
        document.querySelectorAll('input[type="text"]:not(#landingPageUrl)').forEach(input => {
            input.addEventListener('input', updatePreview);
        });

        // Clear preview data on form submit
        document.querySelector('form').addEventListener('submit', function() {
            sessionStorage.removeItem('landing_preview_{{ $mailingList->id }}');
        });

        // Copy landing page URL
        function copyLandingUrl(e) {
            e.preventDefault();
            const input = document.getElementById('landingPageUrl');
            const button = e.target.closest('button');

            // Select and copy the text
            input.select();
            input.setSelectionRange(0, 99999);

            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(input.value).then(() => {
                    showCopySuccess(button);
                }).catch(err => {
                    // Fallback to old method
                    fallbackCopy(input, button);
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(input, button);
            }
        }

        function fallbackCopy(input, button) {
            try {
                document.execCommand('copy');
                showCopySuccess(button);
            } catch (err) {
                alert('Kunne ikke kopiere: ' + err);
            }
        }

        function showCopySuccess(button) {
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fa-solid fa-check me-1"></i> Kopieret!';
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-success');

            setTimeout(() => {
                button.innerHTML = originalHtml;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }, 2000);
        }

        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = preview.querySelector('img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove image
        function removeImage() {
            if (confirm('Er du sikker på at du vil fjerne billedet?')) {
                document.getElementById('remove_image').value = '1';
                document.querySelector('form').submit();
            }
        }

        // Drag and drop functionality
        const uploadZone = document.getElementById('upload-zone');
        const fileInput = document.getElementById('landing_hero_image');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.style.borderColor = 'var(--primary-color)';
                uploadZone.style.background = '#f1f5f9';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.style.borderColor = '#cbd5e1';
                uploadZone.style.background = '#f8fafc';
            }, false);
        });

        uploadZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            previewImage(fileInput);
        }, false);
    </script>
</x-app-layout>
