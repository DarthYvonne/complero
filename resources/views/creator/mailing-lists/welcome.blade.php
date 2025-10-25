<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Grupper</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Velkomst</strong>
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
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-plus me-1"></i> Sign up
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                        <i class="fa-solid fa-code me-2"></i> Forms
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                        <i class="fa-solid fa-qrcode me-2"></i> QR Code
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.landing-page', $mailingList) }}">
                        <i class="fa-solid fa-image me-2"></i> Landing page
                    </a></li>
                </ul>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.welcome', $mailingList) }}">
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

        <!-- Page Description -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">
                    <i class="fa-solid fa-heart me-2" style="color: var(--primary-color);"></i>
                    Velkomst som vises på forsiden
                </h2>
                <button type="submit" form="welcome-form" class="btn btn-primary btn-lg">
                    Gem velkomst
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Content Form -->
        <form id="welcome-form" action="{{ route('creator.mailing-lists.update-welcome', $mailingList) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="row g-4">
                <!-- Left Column: Header and Text -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                Velkomsttekst
                            </h5>
                        </div>
                        <div class="card-body">
                            <p style="font-weight: 300; color: #666; margin-bottom: 20px;">
                                Denne tekst vises øverst på startsiden når medlemmer af denne gruppe logger ind.
                            </p>

                            <!-- Header Field -->
                            <div class="mb-4">
                                <label for="welcome_header" class="form-label" style="font-weight: 500;">
                                    Overskrift
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="welcome_header"
                                       name="welcome_header"
                                       value="{{ old('welcome_header', $mailingList->welcome_header) }}"
                                       placeholder="F.eks. Velkommen til kurset">
                            </div>

                            <!-- Text Field -->
                            <div class="mb-3">
                                <label for="welcome_text" class="form-label" style="font-weight: 500;">
                                    Brødtekst
                                </label>
                                <div id="welcome-editor" style="height: 300px; background: white; border: 1px solid #dee2e6; border-radius: 4px;"></div>
                                <input type="hidden" id="welcome_text" name="welcome_text">
                                <textarea id="welcome_text_initial" style="display:none;">{{ old('welcome_text', $mailingList->welcome_text) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Image -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                Velkomstbillede
                            </h5>
                        </div>
                        <div class="card-body">
                            <p style="font-weight: 300; color: #666; margin-bottom: 20px;">
                                Upload et billede der vises sammen med velkomstteksten.
                            </p>

                            <!-- Current Image Preview -->
                            @if($mailingList->welcome_image)
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Nuværende billede</label>
                                    <div class="text-center">
                                        <img src="{{ asset('files/' . $mailingList->welcome_image) }}"
                                             alt="Velkomstbillede"
                                             style="max-width: 100%; border: 1px solid #e0e0e0; border-radius: 8px;">
                                    </div>
                                </div>
                            @endif

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="welcome_image" class="form-label" style="font-weight: 500;">
                                    {{ $mailingList->welcome_image ? 'Skift billede' : 'Upload billede' }}
                                </label>
                                <input type="file"
                                       class="form-control"
                                       id="welcome_image"
                                       name="welcome_image"
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <div class="form-text">Maksimal filstørrelse: 2MB</div>
                            </div>

                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <label class="form-label" style="font-weight: 500;">Forhåndsvisning</label>
                                <div class="text-center">
                                    <img id="preview-img"
                                         src=""
                                         alt="Forhåndsvisning"
                                         style="max-width: 100%; border: 1px solid #e0e0e0; border-radius: 8px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Include Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

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
        #welcome-editor {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Dropdown on hover */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin-top: 0;
        }
    </style>

    <!-- Include Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        // Initialize Quill editor
        const quill = new Quill('#welcome-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Skriv velkomstteksten her...'
        });

        // Load existing content
        const existingContent = document.getElementById('welcome_text_initial').value;
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }

        // Update hidden field on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            document.getElementById('welcome_text').value = quill.root.innerHTML;
        });

        // Also update on any change to ensure it's always current
        quill.on('text-change', function() {
            document.getElementById('welcome_text').value = quill.root.innerHTML;
        });

        // Image preview function
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
