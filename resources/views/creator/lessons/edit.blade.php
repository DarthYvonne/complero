<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
    <span style="margin: 0 8px;">/</span>
    <span style="color: #999;">{{ $lesson->title }}</span>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Rediger</strong>
@endsection

    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                            Rediger lektion: <span style="font-weight: 100;">{{ $lesson->title }}</span>
                        </h1>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fa-solid fa-eye me-1"></i> Forhåndsvisning
                        </a>
                        <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('creator.courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

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
                                       value="{{ old('title', $lesson->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration and Order -->
                            <div class="row">
                                <div class="col-md-6 mb-0">
                                    <label for="duration_minutes" class="form-label">Varighed (minutter)</label>
                                    <input type="number"
                                           class="form-control @error('duration_minutes') is-invalid @enderror"
                                           id="duration_minutes"
                                           name="duration_minutes"
                                           value="{{ old('duration_minutes', $lesson->duration_minutes) }}"
                                           min="1">
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-0">
                                    <label for="order" class="form-label">Rækkefølge</label>
                                    <input type="number"
                                           class="form-control @error('order') is-invalid @enderror"
                                           id="order"
                                           name="order"
                                           value="{{ old('order', $lesson->order) }}"
                                           min="1">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                @foreach($lesson->tabs as $index => $tab)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link"
                                                id="tab-{{ $tab->id }}-nav"
                                                data-bs-toggle="tab"
                                                data-bs-target="#tab-{{ $tab->id }}"
                                                type="button"
                                                role="tab">
                                            {{ $tab->title }}
                                        </button>
                                    </li>
                                @endforeach
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
                                                  style="display: none;">{{ old('content', $lesson->content) }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Existing Tabs (Editable) -->
                                @foreach($lesson->tabs as $index => $tab)
                                    <div class="tab-pane fade" id="tab-{{ $tab->id }}" role="tabpanel">
                                        <div class="mb-3">
                                            <label class="form-label">Tab titel</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="edit-tab-title-{{ $tab->id }}"
                                                   value="{{ $tab->title }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tab indhold</label>
                                            <div id="edit-tab-content-editor-{{ $tab->id }}" style="min-height: 200px; background: white;"></div>
                                            <textarea id="edit-tab-content-{{ $tab->id }}" style="display: none;">{{ $tab->content }}</textarea>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary" onclick="updateTab({{ $tab->id }})">
                                                <i class="fa-solid fa-save me-1"></i> Gem ændringer
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="deleteTab({{ $tab->id }})">
                                                <i class="fa-solid fa-trash me-1"></i> Slet tab
                                            </button>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Add New Tab -->
                                <div class="tab-pane fade" id="add-new-tab" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="new_tab_title" class="form-label">Tab titel <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               id="new_tab_title"
                                               placeholder="F.eks. Ressourcer eller Noter">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tab_content" class="form-label">Tab indhold <span class="text-danger">*</span></label>
                                        <div id="tab-content-editor" style="min-height: 200px; background: white;"></div>
                                        <textarea id="new_tab_content" style="display: none;"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="submitTabForm()">
                                        <i class="fa-solid fa-plus me-1"></i> Tilføj tab
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">
                                @if($lesson->video_path)
                                    Erstat video
                                @else
                                    Upload video
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Current Video -->
                            @if($lesson->video_path)
                                <div class="mb-3">
                                    <div class="alert alert-info d-flex justify-content-between align-items-center mb-0">
                                        <div>
                                            <i class="fa-solid fa-video me-2"></i>
                                            Video uploadet
                                            <a href="{{ $lesson->getVideoUrl() }}" target="_blank" class="alert-link ms-2">Se video</a>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeVideo()">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" id="remove_video" name="remove_video" value="0">
                                </div>
                            @endif

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
                            <!-- Current Files -->
                            @if($lesson->files->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label">Eksisterende filer</label>
                                    <div class="list-group">
                                        @foreach($lesson->files as $file)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fa-solid fa-file me-2"></i>
                                                    {{ $file->filename }}
                                                    <small class="text-muted">({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                                </div>
                                                <form action="{{ route('creator.courses.lessons.files.destroy', [$course, $lesson, $file]) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Er du sikker på, at du vil slette denne fil?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

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
                            <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
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
                            <div class="list-group list-group-flush">
                                @foreach($course->lessons->sortBy('order') as $index => $courseLesson)
                                    <a href="{{ $courseLesson->id === $lesson->id ? '#' : route('creator.courses.lessons.edit', [$course, $courseLesson]) }}"
                                       class="list-group-item list-group-item-action {{ $courseLesson->id === $lesson->id ? 'active' : '' }}"
                                       style="border-left: none; border-right: none;">
                                        <div class="d-flex align-items-start">
                                            <div class="me-2" style="min-width: 24px;">
                                                <span style="font-size: 13px; font-weight: 300; {{ $courseLesson->id === $lesson->id ? 'color: white;' : 'color: #999;' }}">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div style="font-size: 14px; font-weight: {{ $courseLesson->id === $lesson->id ? '600' : '400' }}; {{ $courseLesson->id === $lesson->id ? 'color: white;' : 'color: #333;' }}">
                                                    {{ $courseLesson->title }}
                                                </div>
                                                @if($courseLesson->duration_minutes)
                                                    <small style="font-size: 12px; font-weight: 300; {{ $courseLesson->id === $lesson->id ? 'color: rgba(255,255,255,0.8);' : 'color: #999;' }}">
                                                        {{ $courseLesson->duration_minutes }} min
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
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
        .list-group-item.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
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

            // Load existing content
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

            // Initialize Quill editor for new tab content
            const tabQuill = new Quill('#tab-content-editor', {
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
                placeholder: 'Skriv indhold til tab...'
            });

            // Sync tab Quill content
            tabQuill.on('text-change', function() {
                document.querySelector('#new_tab_content').value = tabQuill.root.innerHTML;
            });

            // Initialize Quill editors for existing tabs
            const editQuills = {};
            @foreach($lesson->tabs as $tab)
                editQuills[{{ $tab->id }}] = new Quill('#edit-tab-content-editor-{{ $tab->id }}', {
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
                    }
                });

                // Load existing content
                const existingTabContent{{ $tab->id }} = document.querySelector('#edit-tab-content-{{ $tab->id }}').value;
                if (existingTabContent{{ $tab->id }}) {
                    editQuills[{{ $tab->id }}].root.innerHTML = existingTabContent{{ $tab->id }};
                }

                // Sync content on change
                editQuills[{{ $tab->id }}].on('text-change', function() {
                    document.querySelector('#edit-tab-content-{{ $tab->id }}').value = editQuills[{{ $tab->id }}].root.innerHTML;
                });
            @endforeach
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

        // Remove current video
        function removeVideo() {
            if (confirm('Er du sikker på, at du vil fjerne denne video?')) {
                document.getElementById('remove_video').value = '1';
                document.querySelector('form').submit();
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

        // Submit new tab via AJAX
        function submitTabForm() {
            const title = document.getElementById('new_tab_title').value;
            const content = document.getElementById('new_tab_content').value;

            if (!title || !content) {
                alert('Udfyld venligst både titel og indhold');
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('title', title);
            formData.append('content', content);

            fetch('{{ route('creator.courses.lessons.tabs.store', [$course, $lesson]) }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Der opstod en fejl. Prøv igen.');
                }
            });
        }

        // Update existing tab
        function updateTab(tabId) {
            const title = document.getElementById('edit-tab-title-' + tabId).value;
            const content = document.getElementById('edit-tab-content-' + tabId).value;

            if (!title || !content) {
                alert('Udfyld venligst både titel og indhold');
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            formData.append('title', title);
            formData.append('content', content);

            fetch(`{{ route('creator.courses.lessons.tabs.store', [$course, $lesson]) }}/${tabId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Der opstod en fejl. Prøv igen.');
                }
            });
        }

        // Delete tab
        function deleteTab(tabId) {
            if (!confirm('Er du sikker på, at du vil slette denne tab?')) {
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch(`{{ route('creator.courses.lessons.tabs.store', [$course, $lesson]) }}/${tabId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Der opstod en fejl. Prøv igen.');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
