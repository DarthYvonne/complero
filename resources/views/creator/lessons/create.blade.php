<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Tilføj lektion</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Tilføj lektion
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Tilføj en ny lektion til {{ $course->title }}
                    </p>
                </div>
                <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                </a>
            </div>
        </div>

        <!-- Lesson Form -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('creator.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Titel <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Indhold</label>
                                <div id="content-editor" style="min-height: 250px; background: white;"></div>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content"
                                          name="content"
                                          style="display: none;">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Beskriv lektionens indhold</div>
                            </div>

                            <!-- Duration -->
                            <div class="mb-3">
                                <label for="duration_minutes" class="form-label">Varighed (minutter)</label>
                                <input type="number"
                                       class="form-control @error('duration_minutes') is-invalid @enderror"
                                       id="duration_minutes"
                                       name="duration_minutes"
                                       value="{{ old('duration_minutes') }}"
                                       min="1">
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Video Upload -->
                            <div class="mb-3">
                                <label for="video" class="form-label">Video (MP4, MOV, AVI, WebM)</label>
                                <input type="file"
                                       class="form-control @error('video') is-invalid @enderror"
                                       id="video"
                                       name="video"
                                       accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Maksimal filstørrelse: 500MB</div>
                            </div>

                            <!-- File Attachments -->
                            <div class="mb-4">
                                <label for="files" class="form-label">Vedhæftede filer</label>
                                <input type="file"
                                       class="form-control @error('files.*') is-invalid @enderror"
                                       id="files"
                                       name="files[]"
                                       multiple>
                                @error('files.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Upload PDF'er, dokumenter, eller andre filer (max 10MB per fil)</div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-check me-1"></i> Opret lektion
                                </button>
                                <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-color);"></i> Hjælp
                        </h5>
                        <div style="font-size: 14px; font-weight: 300; color: #666;">
                            <p><strong>Titel:</strong> Vælg en klar titel for lektionen.</p>
                            <p><strong>Indhold:</strong> Beskriv hvad deltagerne lærer i denne lektion.</p>
                            <p><strong>Video:</strong> Upload en MP4-videofil (anbefalet format).</p>
                            <p><strong>Filer:</strong> Tilføj PDF'er, dokumenter eller andre ressourcer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
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
        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor for content
            const quill = new Quill('#content-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: 'Beskriv lektionens indhold...'
            });

            // Load existing content if any
            const existingContent = document.querySelector('#content').value;
            if (existingContent) {
                quill.root.innerHTML = existingContent;
            }

            // Sync Quill content to hidden textarea on form submission
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                document.querySelector('#content').value = quill.root.innerHTML;
            });

            // Also sync on text change
            quill.on('text-change', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        });
    </script>
    @endpush
</x-app-layout>
