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
                                        <small class="text-muted ms-2">(<span id="video-filesize"></span>)</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="clearVideoUpload()">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Progress Bar -->
                            <div id="upload-progress" class="mt-3" style="display: none;">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="fa-solid fa-upload me-2"></i>Uploader video...</span>
                                            <span id="upload-percentage">0%</span>
                                        </div>
                                        <div class="progress" style="height: 25px;">
                                            <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                                                 role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                0%
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            <span id="upload-speed"></span> • <span id="upload-time-remaining"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Error Display -->
                            <div id="upload-error" class="mt-3" style="display: none;">
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading"><i class="fa-solid fa-exclamation-triangle me-2"></i>Upload fejlede</h6>
                                    <p class="mb-0" id="upload-error-message"></p>
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

                // Check if there's a video file being uploaded
                const videoInput = document.getElementById('video');
                if (videoInput && videoInput.files && videoInput.files[0]) {
                    e.preventDefault(); // Prevent normal form submission
                    uploadFormWithProgress(form);
                }
            });

            // Also sync on text change
            quill.on('text-change', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        });

        // Preview video with size validation
        function previewVideo(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 500 * 1024 * 1024; // 500MB in bytes

                // Hide previous errors
                document.getElementById('upload-error').style.display = 'none';

                // Validate file size
                if (file.size > maxSize) {
                    document.getElementById('upload-error-message').innerHTML =
                        `Filen er for stor (${formatFileSize(file.size)}). Maksimal størrelse er 500MB.`;
                    document.getElementById('upload-error').style.display = 'block';
                    input.value = ''; // Clear the input
                    return;
                }

                document.getElementById('video-filename').textContent = file.name;
                document.getElementById('video-filesize').textContent = formatFileSize(file.size);
                document.getElementById('video-preview').style.display = 'block';
            }
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
        }

        // Clear video upload
        function clearVideoUpload() {
            document.getElementById('video').value = '';
            document.getElementById('video-preview').style.display = 'none';
        }

        // Upload form with progress tracking
        function uploadFormWithProgress(form) {
            const formData = new FormData(form);
            const videoInput = document.getElementById('video');

            // Show progress bar
            document.getElementById('upload-progress').style.display = 'block';
            document.getElementById('upload-error').style.display = 'none';
            document.getElementById('video-preview').style.display = 'none';

            // Disable submit button to prevent double submission
            const submitButtons = form.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(btn => btn.disabled = true);

            const xhr = new XMLHttpRequest();
            let startTime = Date.now();
            let lastLoaded = 0;

            // Track upload progress
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    const progressBar = document.getElementById('upload-progress-bar');
                    const percentText = document.getElementById('upload-percentage');

                    progressBar.style.width = percentComplete + '%';
                    progressBar.textContent = percentComplete + '%';
                    progressBar.setAttribute('aria-valuenow', percentComplete);
                    percentText.textContent = percentComplete + '%';

                    // Calculate upload speed
                    const elapsedTime = (Date.now() - startTime) / 1000; // seconds
                    const uploadSpeed = e.loaded / elapsedTime; // bytes per second
                    const speedText = formatFileSize(uploadSpeed) + '/s';
                    document.getElementById('upload-speed').textContent = speedText;

                    // Calculate time remaining
                    const bytesRemaining = e.total - e.loaded;
                    const secondsRemaining = Math.round(bytesRemaining / uploadSpeed);
                    const timeText = formatTime(secondsRemaining);
                    document.getElementById('upload-time-remaining').textContent = 'Ca. ' + timeText + ' tilbage';
                }
            });

            // Handle completion
            xhr.addEventListener('load', function() {
                if (xhr.status === 200 || xhr.status === 302) {
                    // Success! Redirect or reload
                    const progressBar = document.getElementById('upload-progress-bar');
                    progressBar.classList.remove('progress-bar-animated');
                    progressBar.classList.add('bg-success');
                    progressBar.textContent = 'Færdig!';

                    setTimeout(function() {
                        // Check if response has redirect
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.reload();
                            }
                        } catch (e) {
                            // Not JSON, likely a redirect - reload the page
                            window.location.reload();
                        }
                    }, 500);
                } else {
                    // Error occurred
                    handleUploadError(xhr);
                    submitButtons.forEach(btn => btn.disabled = false);
                }
            });

            // Handle errors
            xhr.addEventListener('error', function() {
                handleUploadError(xhr, 'Netværksfejl. Tjek din internetforbindelse og prøv igen.');
                submitButtons.forEach(btn => btn.disabled = false);
            });

            xhr.addEventListener('abort', function() {
                handleUploadError(xhr, 'Upload blev afbrudt.');
                submitButtons.forEach(btn => btn.disabled = false);
            });

            xhr.addEventListener('timeout', function() {
                handleUploadError(xhr, 'Upload timeout. Filen er muligvis for stor eller din forbindelse er for langsom.');
                submitButtons.forEach(btn => btn.disabled = false);
            });

            // Send the request
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.timeout = 600000; // 10 minutes timeout
            xhr.send(formData);
        }

        // Handle upload errors
        function handleUploadError(xhr, defaultMessage = null) {
            document.getElementById('upload-progress').style.display = 'none';

            let errorMessage = defaultMessage || 'Der opstod en ukendt fejl under upload.';

            if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        // Laravel validation errors
                        errorMessage = '<ul class="mb-0">';
                        for (const field in response.errors) {
                            response.errors[field].forEach(err => {
                                errorMessage += `<li>${err}</li>`;
                            });
                        }
                        errorMessage += '</ul>';
                    } else if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    // Not JSON, try to extract error from HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');
                    const errorTitle = doc.querySelector('title');
                    if (errorTitle) {
                        errorMessage = errorTitle.textContent;
                    }
                }
            }

            // Add server status information
            if (xhr.status) {
                errorMessage += `<br><small class="text-muted">HTTP Status: ${xhr.status}</small>`;
            }

            document.getElementById('upload-error-message').innerHTML = errorMessage;
            document.getElementById('upload-error').style.display = 'block';

            // Scroll to error
            document.getElementById('upload-error').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Format time in seconds to readable format
        function formatTime(seconds) {
            if (seconds < 60) {
                return seconds + ' sekunder';
            } else if (seconds < 3600) {
                const minutes = Math.floor(seconds / 60);
                return minutes + ' minut' + (minutes > 1 ? 'ter' : '');
            } else {
                const hours = Math.floor(seconds / 3600);
                const minutes = Math.floor((seconds % 3600) / 60);
                return hours + ' time' + (hours > 1 ? 'r' : '') + (minutes > 0 ? ' og ' + minutes + ' minut' + (minutes > 1 ? 'ter' : '') : '');
            }
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
