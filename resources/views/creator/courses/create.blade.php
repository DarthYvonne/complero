<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Opret forløb</strong>
@endsection
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                            Opret nyt forløb
                        </h1>
                        <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">Udfyld formularen for at oprette et nyt forløb</p>
                    </div>
                    <a href="{{ route('creator.courses.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('creator.courses.store') }}" method="POST" enctype="multipart/form-data">
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
                                <div class="form-text">Dette vil også bruges til at generere URL-slug</div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Beskrivelse <span class="text-danger">*</span></label>
                                <div id="description-editor" style="min-height: 200px; background: white;"></div>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          style="display: none;"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Kursus billede</label>
                                <input type="file"
                                       class="form-control @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maksimal filstørrelse: 2MB. Anbefalet format: JPG eller PNG</div>
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label for="price" class="form-label">Pris (€)</label>
                                <input type="number"
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="price"
                                       name="price"
                                       value="{{ old('price', '0') }}"
                                       step="0.01"
                                       min="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Sæt til 0 for gratis forløb</div>
                            </div>

                            <!-- Mailing List -->
                            <div class="mb-3">
                                <label for="mailing_list_id" class="form-label">Tilgængelig for</label>
                                <select class="form-select @error('mailing_list_id') is-invalid @enderror"
                                        id="mailing_list_id"
                                        name="mailing_list_id">
                                    <option value="">Alle (gratis for alle)</option>
                                    @foreach($mailingLists as $list)
                                        <option value="{{ $list->id }}" {{ old('mailing_list_id') == $list->id ? 'selected' : '' }}>
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
                                           {{ old('is_free', true) ? 'checked' : '' }}>
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
                                           {{ old('is_published') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">
                                        Publicer forløb (gør det synligt for brugere)
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-check me-1"></i> Opret forløb
                                </button>
                                <a href="{{ route('creator.courses.index') }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-circle-info text-primary"></i> Hjælp
                        </h5>
                        <div class="small">
                            <p><strong>Titel:</strong> Vælg en klar og beskrivende titel for dit forløb.</p>
                            <p><strong>Beskrivelse:</strong> Forklar hvad deltagerne vil lære, og hvem kurset er for.</p>
                            <p><strong>Billede:</strong> Upload et attraktivt billede der repræsenterer kurset.</p>
                            <p><strong>Pris:</strong> Sæt prisen til 0 for gratis forløb, eller angiv beløbet i euro.</p>
                            <p><strong>Publicering:</strong> Forløb er som standard kladder. Markér "Publicer" når du er klar til at gøre det synligt.</p>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fa-solid fa-lightbulb text-warning"></i> Tips
                        </h5>
                        <div class="small text-muted">
                            <p>Efter du har oprettet kurset, kan du tilføje lektioner med videoer og filer.</p>
                            <p>Du kan altid redigere kurset senere og tilføje mere indhold.</p>
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

            // Load existing content if any
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
        });
    </script>
    @endpush
</x-app-layout>
