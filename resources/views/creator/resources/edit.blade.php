<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.resources.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Downloads</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.resources.show', $resource) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $resource->title }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Rediger</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Rediger download
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        {{ $resource->title }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('resources.show', $resource) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fa-solid fa-eye me-1"></i> Forhåndsvisning
                    </a>
                    <a href="{{ route('creator.resources.show', $resource) }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til download
                    </a>
                </div>
            </div>
        </div>

        <!-- Resource Form -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('creator.resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Titel <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title', $resource->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Beskrivelse <span class="text-danger">*</span></label>
                                <div id="description-editor" style="min-height: 200px; background: white;"></div>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          style="display: none;"
                                          required>{{ old('description', $resource->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Image -->
                            @if($resource->image_url)
                                <div class="mb-3">
                                    <label class="form-label">Nuværende billede</label>
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($resource->image_url) }}" alt="{{ $resource->title }}" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="remove_image"
                                               name="remove_image"
                                               value="1">
                                        <label class="form-check-label" for="remove_image">
                                            Fjern billede
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    @if($resource->image_url)
                                        Nyt billede (valgfrit)
                                    @else
                                        Downloads billede
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
                                <div class="form-text" style="font-weight: 300;">
                                    @if($resource->image_url)
                                        Upload kun hvis du vil erstatte det nuværende billede. Maksimal filstørrelse: 2MB
                                    @else
                                        Maksimal filstørrelse: 2MB. Anbefalet format: JPG eller PNG
                                    @endif
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Pris (€)</label>
                                <input type="number"
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="price"
                                       name="price"
                                       value="{{ old('price', $resource->price) }}"
                                       step="0.01"
                                       min="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Sæt til 0 for gratis download</div>
                            </div>

                            <!-- Current Files -->
                            @if($resource->files->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label">Eksisterende filer</label>
                                    <div class="list-group">
                                        @foreach($resource->files as $file)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fa-solid fa-file me-2"></i>
                                                    {{ $file->filename }}
                                                    <small class="text-muted">({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                                </div>
                                                <form action="{{ route('creator.resources.files.destroy', [$resource, $file]) }}" method="POST" class="d-inline"
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
                            <div class="mb-3">
                                <label for="files" class="form-label">Tilføj flere filer</label>
                                <input type="file"
                                       class="form-control @error('files.*') is-invalid @enderror"
                                       id="files"
                                       name="files[]"
                                       multiple>
                                @error('files.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Upload PDF'er, e-bøger, skabeloner eller andre filer (max 10MB per fil)</div>
                            </div>

                            <!-- Mailing List -->
                            <div class="mb-3">
                                <label for="mailing_list_id" class="form-label">Tilgængelig for</label>
                                <select class="form-select @error('mailing_list_id') is-invalid @enderror"
                                        id="mailing_list_id"
                                        name="mailing_list_id">
                                    <option value="">Alle (gratis for alle)</option>
                                    @foreach($mailingLists as $list)
                                        <option value="{{ $list->id }}" {{ old('mailing_list_id', $resource->mailing_list_id) == $list->id ? 'selected' : '' }}>
                                            For medlemmer af {{ $list->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mailing_list_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text" style="font-weight: 300;">Vælg en mailing liste for at begrænse adgang, eller lad feltet være tomt for gratis adgang for alle</div>
                            </div>

                            <!-- Checkboxes -->
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_free"
                                           name="is_free"
                                           value="1"
                                           {{ old('is_free', $resource->is_free) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_free">
                                        Dette download er gratis
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_published"
                                           name="is_published"
                                           value="1"
                                           {{ old('is_published', $resource->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publicer download (gør det synligt for brugere)
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
                                </button>
                                <a href="{{ route('creator.resources.show', $resource) }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-color);"></i> Downloadsinfo
                        </h5>
                        <div style="font-size: 14px; font-weight: 300; color: #666;">
                            <div class="mb-3">
                                <strong class="d-block mb-1">Oprettet</strong>
                                {{ $resource->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Sidst opdateret</strong>
                                {{ $resource->updated_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Slug</strong>
                                <code>{{ $resource->slug }}</code>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Oprettet af</strong>
                                {{ $resource->creator->name }}
                            </div>

                            <div>
                                <strong class="d-block mb-1">Antal filer</strong>
                                {{ $resource->files->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Tabs Management -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Ekstra tabs (valgfrit)</h5>
                <small class="text-muted">Tilføj ekstra tabs til downloadts side. "Intro" tab vises altid med beskrivelsen.</small>
            </div>
            <div class="card-body">
                <!-- Existing Tabs -->
                @if($resource->tabs->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">Eksisterende tabs</h6>
                        <div class="list-group">
                            @foreach($resource->tabs as $tab)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $tab->title }}</strong>
                                        <div class="small text-muted mt-1">{{ Str::limit(strip_tags($tab->content), 100) }}</div>
                                    </div>
                                    <form action="{{ route('creator.resources.tabs.destroy', [$resource, $tab]) }}" method="POST" class="d-inline"
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
                    <form action="{{ route('creator.resources.tabs.store', $resource) }}" method="POST">
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
                placeholder: 'Skriv en beskrivelse af downloadt...'
            });

            // Load existing content
            const existingContent = document.querySelector('#description').value;
            if (existingContent) {
                quill.root.innerHTML = existingContent;
            }

            // Sync Quill content to hidden textarea on form submission
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
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
