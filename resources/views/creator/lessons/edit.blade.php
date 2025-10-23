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

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                        <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> {{ $course->title }}: <span style="font-weight: 100;">{{ $lesson->title }}</span>
                    </h1>
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
                        <form action="{{ route('creator.courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

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

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Indhold</label>
                                <div id="content-editor" style="min-height: 250px; background: white;"></div>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content"
                                          name="content"
                                          style="display: none;">{{ old('content', $lesson->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration and Order -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
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

                                <div class="col-md-6 mb-3">
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

                            <!-- Current Video -->
                            @if($lesson->video_path)
                                <div class="mb-3">
                                    <label class="form-label">Nuværende video</label>
                                    <div class="alert alert-info mb-2">
                                        <i class="fa-solid fa-video me-2"></i>
                                        Video uploadet
                                        <a href="{{ Storage::url($lesson->video_path) }}" target="_blank" class="alert-link">Se video</a>
                                    </div>
                                </div>
                            @endif

                            <!-- Video Upload -->
                            <div class="mb-3">
                                <label for="video" class="form-label">
                                    @if($lesson->video_path)
                                        Nyt video (valgfrit)
                                    @else
                                        Video (MP4, MOV, AVI, WebM)
                                    @endif
                                </label>
                                <input type="file"
                                       class="form-control @error('video') is-invalid @enderror"
                                       id="video"
                                       name="video"
                                       accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">
                                    @if($lesson->video_path)
                                        Upload kun hvis du vil erstatte den nuværende video. Maksimal filstørrelse: 500MB
                                    @else
                                        Maksimal filstørrelse: 500MB
                                    @endif
                                </div>
                            </div>

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
                                                        <i class="fa-solid fa-trash"></i> Slet
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- New File Attachments -->
                            <div class="mb-4">
                                <label for="files" class="form-label">Tilføj flere filer</label>
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
                                    <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
                                </button>
                                <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lesson Tabs Management -->
                <div class="card mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Ekstra tabs (valgfrit)</h5>
                        <small class="text-muted">Tilføj ekstra tabs til lektionens side. "Intro" tab vises altid med indholdet.</small>
                    </div>
                    <div class="card-body">
                        <!-- Existing Tabs -->
                        @if($lesson->tabs->count() > 0)
                            <div class="mb-4">
                                <h6 class="mb-3">Eksisterende tabs</h6>
                                <div class="list-group">
                                    @foreach($lesson->tabs as $tab)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $tab->title }}</strong>
                                                <div class="small text-muted mt-1">{{ Str::limit(strip_tags($tab->content), 100) }}</div>
                                            </div>
                                            <form action="{{ route('creator.courses.lessons.tabs.destroy', [$course, $lesson, $tab]) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Er du sikker på, at du vil slette denne tab?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i> Slet
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Add New Tab Form -->
                        <div>
                            <h6 class="mb-3">Tilføj ny tab</h6>
                            <form action="{{ route('creator.courses.lessons.tabs.store', [$course, $lesson]) }}" method="POST">
                                @csrf

                                <!-- Tab Title -->
                                <div class="mb-3">
                                    <label for="tab_title" class="form-label">Tab titel <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="tab_title"
                                           name="title"
                                           placeholder="F.eks. Resourcer, Noter, eller Ofte stillede spørgsmål"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tab Content -->
                                <div class="mb-3">
                                    <label for="tab_content" class="form-label">Tab indhold <span class="text-danger">*</span></label>
                                    <div id="tab-content-editor" style="min-height: 200px; background: white;"></div>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="tab_content"
                                              name="content"
                                              style="display: none;"
                                              required></textarea>
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-1"></i> Tilføj tab
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-color);"></i> Lektionsinfo
                        </h5>
                        <div style="font-size: 14px; font-weight: 300; color: #666;">
                            <div class="mb-3">
                                <strong class="d-block mb-1">Oprettet</strong>
                                {{ $lesson->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Sidst opdateret</strong>
                                {{ $lesson->updated_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Slug</strong>
                                <code>{{ $lesson->slug }}</code>
                            </div>
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

            // Initialize Quill editor for tab content
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

            // Sync tab Quill content to hidden textarea
            const tabForm = document.querySelectorAll('form')[1]; // Second form on page
            if (tabForm) {
                tabForm.addEventListener('submit', function(e) {
                    document.querySelector('#tab_content').value = tabQuill.root.innerHTML;
                });

                tabQuill.on('text-change', function() {
                    document.querySelector('#tab_content').value = tabQuill.root.innerHTML;
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
