<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
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
                            Rediger forløb
                        </h1>
                        <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">{{ $course->title }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fa-solid fa-eye me-1"></i> Forhåndsvisning
                        </a>
                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Titel <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title', $course->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Nuværende slug: <code>{{ $course->slug }}</code></div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Beskrivelse <span class="text-danger">*</span></label>
                                <div id="description-editor" style="min-height: 200px; background: white;"></div>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          style="display: none;"
                                          required>{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Image -->
                            @if($course->image_url)
                                <div class="mb-3">
                                    <label class="form-label">Nuværende billede</label>
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($course->image_url) }}"
                                             alt="{{ $course->title }}"
                                             class="img-thumbnail"
                                             style="max-height: 200px;">
                                    </div>
                                </div>
                            @endif

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    @if($course->image_url)
                                        Nyt billede (valgfrit)
                                    @else
                                        Kursus billede
                                    @endif
                                </label>
                                <input type="file"
                                       class="form-control @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maksimal filstørrelse: 2MB. Upload kun hvis du vil erstatte det nuværende billede</div>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Pris (€)</label>
                                <input type="number"
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="price"
                                       name="price"
                                       value="{{ old('price', $course->price ?? '0') }}"
                                       step="0.01"
                                       min="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Sæt til 0 for gratis kurser</div>
                            </div>

                            <!-- Mailing List -->
                            <div class="mb-3">
                                <label for="mailing_list_id" class="form-label">Tilgængelig for</label>
                                <select class="form-select @error('mailing_list_id') is-invalid @enderror"
                                        id="mailing_list_id"
                                        name="mailing_list_id">
                                    <option value="">Alle (gratis for alle)</option>
                                    @foreach($mailingLists as $list)
                                        <option value="{{ $list->id }}" {{ old('mailing_list_id', $course->mailing_list_id) == $list->id ? 'selected' : '' }}>
                                            For medlemmer af {{ $list->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mailing_list_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Vælg en mailing liste for at begrænse adgang, eller lad feltet være tomt for gratis adgang for alle</div>
                            </div>

                            <!-- Checkboxes -->
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_free"
                                           name="is_free"
                                           value="1"
                                           {{ old('is_free', $course->is_free) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">
                                        Dette forløb er gratis
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_published"
                                           name="is_published"
                                           value="1"
                                           {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publicer forløb (gør det synligt for brugere)
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
                                </button>
                                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Course Tabs Management -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Ekstra tabs (valgfrit)</h5>
                        <small class="text-muted">Tilføj ekstra tabs til kursets side. "Intro" tab vises altid med beskrivelsen.</small>
                    </div>
                    <div class="card-body">
                        <!-- Existing Tabs -->
                        @if($course->tabs->count() > 0)
                            <div class="mb-4">
                                <h6 class="mb-3">Eksisterende tabs</h6>
                                <div class="list-group">
                                    @foreach($course->tabs as $tab)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $tab->title }}</strong>
                                                <div class="small text-muted mt-1">{{ Str::limit(strip_tags($tab->content), 100) }}</div>
                                            </div>
                                            <form action="{{ route('admin.courses.tabs.destroy', [$course, $tab]) }}" method="POST" class="d-inline"
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
                            <form action="{{ route('admin.courses.tabs.store', $course) }}" method="POST">
                                @csrf

                                <!-- Tab Title -->
                                <div class="mb-3">
                                    <label for="tab_title" class="form-label">Tab titel <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="tab_title"
                                           name="title"
                                           placeholder="F.eks. Ofte stillede spørgsmål"
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

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-circle-info text-primary"></i> Kursus information
                        </h5>
                        <div class="small">
                            <div class="mb-3">
                                <strong class="d-block text-muted mb-1">Oprettet</strong>
                                {{ $course->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted mb-1">Sidst opdateret</strong>
                                {{ $course->updated_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted mb-1">Oprettet af</strong>
                                {{ $course->creator->name }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted mb-1">Antal lektioner</strong>
                                {{ $course->lessons->count() }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block text-muted mb-1">Tilmeldinger</strong>
                                {{ $course->enrollments->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-triangle-exclamation text-warning"></i> Bemærk
                        </h5>
                        <div class="small text-muted">
                            <p>Hvis du ændrer titel, vil slug forblive det samme for at bevare eksisterende links.</p>
                            <p>Ændringer vil blive gemt med det samme når du klikker "Gem ændringer".</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill editor for description
            const quill = new Quill('#description-editor', {
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
                placeholder: 'Skriv en beskrivelse af kurset...'
            });

            // Load existing content
            const existingContent = document.querySelector('#description').value;
            if (existingContent) {
                quill.root.innerHTML = existingContent;
            }

            // Sync Quill content to hidden textarea on form submission
            const courseForm = document.querySelector('form');
            courseForm.addEventListener('submit', function(e) {
                document.querySelector('#description').value = quill.root.innerHTML;
            });

            // Also sync on text change (for validation)
            quill.on('text-change', function() {
                document.querySelector('#description').value = quill.root.innerHTML;
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
            tabForm.addEventListener('submit', function(e) {
                document.querySelector('#tab_content').value = tabQuill.root.innerHTML;
            });

            tabQuill.on('text-change', function() {
                document.querySelector('#tab_content').value = tabQuill.root.innerHTML;
            });
        });
    </script>
    @endpush
</x-app-layout>
