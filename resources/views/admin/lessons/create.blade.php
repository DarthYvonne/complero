<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
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
                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                </a>
            </div>
        </div>

        <!-- Lesson Form -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data">
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
                                       accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                       onchange="previewVideo(this)">
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Maksimal filstørrelse: 500MB</div>
                            </div>

                            <!-- Video Preview -->
                            <div id="video-preview" class="mb-3" style="display: none;">
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
                            <div id="upload-progress" class="mb-3" style="display: none;">
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
                            <div id="upload-error" class="mb-3" style="display: none;">
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading"><i class="fa-solid fa-exclamation-triangle me-2"></i>Upload fejlede</h6>
                                    <p class="mb-0" id="upload-error-message"></p>
                                </div>
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
                                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-secondary">
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
    </script>
    @endpush
</x-app-layout>
