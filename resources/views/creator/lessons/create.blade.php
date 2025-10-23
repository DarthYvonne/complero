<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Tilføj lektion</strong>
@endsection

    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                            Tilføj ny lektion
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
        </div>

        <form action="{{ route('creator.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Grundlæggende information</h5>
                        </div>
                        <div class="card-body">
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

                            <!-- Duration -->
                            <div class="mb-0">
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
                        </div>
                    </div>

                    <!-- Content Card with Tabs -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Indhold</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs mb-3" id="lessonTabsNav" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active"
                                            id="intro-tab-nav"
                                            data-bs-toggle="tab"
                                            data-bs-target="#intro-tab"
                                            type="button"
                                            role="tab">
                                        Introduktion
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link"
                                            id="add-new-tab-nav"
                                            data-bs-toggle="tab"
                                            data-bs-target="#add-new-tab"
                                            type="button"
                                            role="tab">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="lessonTabsContent">
                                <!-- Introduktion Tab -->
                                <div class="tab-pane fade show active" id="intro-tab" role="tabpanel">
                                    <div class="mb-0">
                                        <div id="content-editor" style="min-height: 250px; background: white;"></div>
                                        <textarea class="form-control @error('content') is-invalid @enderror"
                                                  id="content"
                                                  name="content"
                                                  style="display: none;">{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Add New Tab (Note: tabs can only be added after lesson creation) -->
                                <div class="tab-pane fade" id="add-new-tab" role="tabpanel">
                                    <div class="alert alert-info">
                                        <i class="fa-solid fa-circle-info me-2"></i>
                                        Du kan tilføje ekstra tabs efter du har oprettet lektionen.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Upload video</h5>
                        </div>
                        <div class="card-body">
                            <!-- Video Upload Drop Zone -->
                            <div class="upload-zone" onclick="document.getElementById('video').click()">
                                <input type="file"
                                       class="d-none @error('video') is-invalid @enderror"
                                       id="video"
                                       name="video"
                                       accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                       onchange="previewVideo(this)">
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3rem; color: #cbd5e1;"></i>
                                    <p class="mb-1 mt-3" style="font-weight: 500; color: #64748b;">
                                        Klik for at vælge video
                                    </p>
                                    <p class="small text-muted mb-0">
                                        Max 500MB • MP4, MOV, AVI, WebM
                                    </p>
                                </div>
                            </div>
                            @error('video')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Video Preview -->
                            <div id="video-preview" class="mt-3" style="display: none;">
                                <div class="alert alert-success d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fa-solid fa-video me-2"></i>
                                        <span id="video-filename"></span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="clearVideoUpload()">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Filer</h5>
                        </div>
                        <div class="card-body">
                            <!-- File Upload Drop Zone -->
                            <div class="upload-zone" onclick="document.getElementById('files').click()">
                                <input type="file"
                                       class="d-none @error('files.*') is-invalid @enderror"
                                       id="files"
                                       name="files[]"
                                       multiple
                                       onchange="previewFiles(this)">
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3rem; color: #cbd5e1;"></i>
                                    <p class="mb-1 mt-3" style="font-weight: 500; color: #64748b;">
                                        Klik for at vælge filer
                                    </p>
                                    <p class="small text-muted mb-0">
                                        Max 10MB per fil • PDF, dokumenter, etc.
                                    </p>
                                </div>
                            </div>
                            @error('files.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Files Preview -->
                            <div id="files-preview" class="mt-3" style="display: none;">
                                <div class="alert alert-success">
                                    <i class="fa-solid fa-file me-2"></i>
                                    <span id="files-count"></span> fil(er) valgt
                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearFilesUpload()">
                                        <i class="fa-solid fa-times"></i> Annuller
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-circle-check me-1"></i> Opret lektion
                        </button>
                        <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-outline-secondary">
                            Annuller
                        </a>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Course Plan Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Forløbsplan</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($course->lessons->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($course->lessons->sortBy('order') as $index => $lesson)
                                        <a href="{{ route('creator.courses.lessons.edit', [$course, $lesson]) }}"
                                           class="list-group-item list-group-item-action"
                                           style="border-left: none; border-right: none;">
                                            <div class="d-flex align-items-start">
                                                <div class="me-2" style="min-width: 24px;">
                                                    <span style="font-size: 13px; font-weight: 300; color: #999;">{{ $index + 1 }}</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div style="font-size: 14px; font-weight: 400; color: #333;">
                                                        {{ $lesson->title }}
                                                    </div>
                                                    @if($lesson->duration_minutes)
                                                        <small style="font-size: 12px; font-weight: 300; color: #999;">
                                                            {{ $lesson->duration_minutes }} min
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">
                                    <i class="fa-solid fa-circle-play" style="font-size: 2rem; color: #e0e0e0;"></i>
                                    <p class="mt-2 mb-0 small">Ingen lektioner endnu</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background-color: #f8fafc;
        }
        .upload-zone:hover {
            border-color: var(--primary-color);
            background-color: #f1f5f9;
        }
        .upload-zone:hover i {
            color: var(--primary-color) !important;
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

        // Preview video
        function previewVideo(input) {
            if (input.files && input.files[0]) {
                document.getElementById('video-filename').textContent = input.files[0].name;
                document.getElementById('video-preview').style.display = 'block';
            }
        }

        // Clear video upload
        function clearVideoUpload() {
            document.getElementById('video').value = '';
            document.getElementById('video-preview').style.display = 'none';
        }

        // Preview files
        function previewFiles(input) {
            if (input.files && input.files.length > 0) {
                document.getElementById('files-count').textContent = input.files.length;
                document.getElementById('files-preview').style.display = 'block';
            }
        }

        // Clear files upload
        function clearFilesUpload() {
            document.getElementById('files').value = '';
            document.getElementById('files-preview').style.display = 'none';
        }
    </script>
    @endpush
</x-app-layout>
