<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
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
                            Rediger forløb: <span style="font-weight: 100;">{{ $course->title }}</span>
                        </h1>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fa-solid fa-eye me-1"></i> Forhåndsvisning
                        </a>
                        <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('creator.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
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
                                       value="{{ old('title', $course->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Short Description -->
                            <div class="mb-0">
                                <label for="short_description" class="form-label">Kort beskrivelse</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror"
                                          id="short_description"
                                          name="short_description"
                                          rows="3"
                                          placeholder="En kort beskrivelse til kort-visning">{{ old('short_description', $course->short_description ?? '') }}</textarea>
                                @error('short_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Denne tekst vises på forløbskort</div>
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
                            <ul class="nav nav-tabs mb-3" id="courseTabsNav" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active"
                                            id="intro-tab-nav"
                                            data-bs-toggle="tab"
                                            data-bs-target="#intro-tab"
                                            type="button"
                                            role="tab">
                                        {{ $course->intro_title ?? 'Introduktion' }}
                                    </button>
                                </li>
                                @foreach($course->tabs as $index => $tab)
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
                            <div class="tab-content" id="courseTabsContent">
                                <!-- Introduktion Tab (Description) -->
                                <div class="tab-pane fade show active" id="intro-tab" role="tabpanel">
                                    <div class="mb-3">
                                        <label for="intro_title" class="form-label">Tab titel</label>
                                        <input type="text"
                                               class="form-control @error('intro_title') is-invalid @enderror"
                                               id="intro_title"
                                               name="intro_title"
                                               value="{{ old('intro_title', $course->intro_title ?? 'Introduktion') }}"
                                               placeholder="Introduktion">
                                        @error('intro_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-0">
                                        <label for="description" class="form-label">Tab indhold <span class="text-danger">*</span></label>
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
                                </div>

                                <!-- Existing Tabs -->
                                @foreach($course->tabs as $index => $tab)
                                    <div class="tab-pane fade"
                                         id="tab-{{ $tab->id }}"
                                         role="tabpanel">

                                        <!-- Edit Tab Form -->
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
                                        <label for="tab_title" class="form-label">Tab titel <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               id="tab_title"
                                               name="tab_title"
                                               placeholder="F.eks. Ofte stillede spørgsmål">
                                    </div>

                                    <div class="mb-3">
                                        <label for="tab_content" class="form-label">Tab indhold <span class="text-danger">*</span></label>
                                        <div id="tab-content-editor" style="min-height: 200px; background: white;"></div>
                                        <textarea class="form-control"
                                                  id="tab_content"
                                                  name="tab_content"
                                                  style="display: none;"></textarea>
                                    </div>

                                    <button type="button" class="btn btn-primary" onclick="submitTabForm()">
                                        <i class="fa-solid fa-plus me-1"></i> Tilføj tab
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">
                                @if($course->image_url)
                                    Erstat billede
                                @else
                                    Upload billede
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Current Image -->
                            @if($course->image_url)
                                <div class="mb-3">
                                    <div class="position-relative d-inline-block">
                                        <img src="{{ $course->image }}"
                                             alt="{{ $course->title }}"
                                             class="img-thumbnail"
                                             style="max-height: 200px; max-width: 100%;">
                                        <button type="button"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                onclick="removeImage()"
                                                style="opacity: 0.9;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" id="remove_image" name="remove_image" value="0">
                                </div>
                            @endif

                            <!-- Image Upload Drop Zone -->
                            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('image').click()">
                                <input type="file"
                                       class="d-none @error('image') is-invalid @enderror"
                                       id="image"
                                       name="image"
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3rem; color: #cbd5e1;"></i>
                                    <p class="mb-1 mt-3" style="font-weight: 500; color: #64748b;">
                                        Klik for at vælge billede
                                    </p>
                                    <p class="small text-muted mb-0">
                                        Max 2MB • JPG eller PNG
                                    </p>
                                </div>
                            </div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="preview-img" src="" class="img-thumbnail" style="max-height: 200px; max-width: 100%;">
                                    <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                            onclick="clearImageUpload()"
                                            style="opacity: 0.9;">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
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

                    <!-- Color Card -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Vælg primær farve</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-0">
                                <div class="d-flex align-items-center gap-3">
                                    <select class="form-select @error('primary_color') is-invalid @enderror"
                                            id="primary_color"
                                            name="primary_color"
                                            style="flex: 1;">
                                        <option value="#be185d" {{ old('primary_color', $course->primary_color) == '#be185d' ? 'selected' : '' }}>Magenta (standard)</option>
                                        <option value="#F2CC21" {{ old('primary_color', $course->primary_color) == '#F2CC21' ? 'selected' : '' }}>Gul</option>
                                        <option value="#2B5A18" {{ old('primary_color', $course->primary_color) == '#2B5A18' ? 'selected' : '' }}>Grøn</option>
                                        <option value="#306D7F" {{ old('primary_color', $course->primary_color) == '#306D7F' ? 'selected' : '' }}>Blå</option>
                                    </select>
                                    <div id="color-preview" style="width: 50px; height: 38px; border-radius: 6px; border: 1px solid #ddd; background-color: {{ old('primary_color', $course->primary_color) }};"></div>
                                </div>
                                @error('primary_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                    <!-- Publish Status Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Publiceret</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div style="font-weight: 500; font-size: 15px; color: #333;">
                                        <span id="publish-status-text">{{ $course->is_published ? 'Forløbet er publiceret' : 'Forløbet er ikke publiceret' }}</span>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        {{ $course->is_published ? 'Synligt for brugere' : 'Kun synligt for dig' }}
                                    </div>
                                </div>
                                <div class="form-check form-switch" style="font-size: 1.5rem;">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           role="switch"
                                           id="is_published"
                                           name="is_published"
                                           value="1"
                                           style="cursor: pointer;"
                                           {{ old('is_published', $course->is_published) ? 'checked' : '' }}
                                           onchange="updatePublishStatus(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price and Availability Card -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0" style="font-weight: 600;">Pris og tilgængelighed</h5>
                        </div>
                        <div class="card-body">
                            <!-- Free/Paid Toggle -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div style="font-weight: 500; font-size: 15px; color: #333;">
                                            <span id="price-type-text">{{ old('is_free', $course->is_free) ? 'Forløbet er gratis' : 'Forløbet koster penge' }}</span>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch" style="font-size: 1.5rem;">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               role="switch"
                                               id="price_switch"
                                               style="cursor: pointer;"
                                               {{ old('is_free', $course->is_free) ? '' : 'checked' }}
                                               onchange="togglePriceType(this)">
                                        <input type="hidden" id="is_free" name="is_free" value="{{ old('is_free', $course->is_free) ? '1' : '0' }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <!-- Paid Section -->
                            <div id="paid-section" style="display: {{ old('is_free', $course->is_free) ? 'none' : 'block' }};">
                                <div class="mb-0">
                                    <label for="price" class="form-label">Beløb (DKK)</label>
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
                                </div>
                            </div>

                            <!-- Free Section -->
                            <div id="free-section" style="display: {{ old('is_free', $course->is_free) ? 'block' : 'none' }};">
                                <div class="mb-0">
                                    @foreach($mailingLists as $list)
                                        <div class="form-check">
                                            <input class="form-check-input mailing-list-checkbox"
                                                   type="checkbox"
                                                   id="mailing_list_{{ $list->id }}"
                                                   name="mailing_list_ids[]"
                                                   value="{{ $list->id }}"
                                                   {{ old('mailing_list_id', $course->mailing_list_id) == $list->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mailing_list_{{ $list->id }}">
                                                For medlemmer af <a href="{{ route('creator.mailing-lists.show', $list) }}" target="_blank" onclick="event.stopPropagation();" style="color: #0d6efd; text-decoration: underline;">{{ $list->name }}</a>
                                            </label>
                                        </div>
                                    @endforeach
                                    @error('mailing_list_ids')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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

            // Sync tab Quill content to hidden textarea
            tabQuill.on('text-change', function() {
                document.querySelector('#tab_content').value = tabQuill.root.innerHTML;
            });

            // Initialize Quill editors for existing tabs
            const editQuills = {};
            @foreach($course->tabs as $tab)
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

            // Color preview update
            const colorSelect = document.getElementById('primary_color');
            const colorPreview = document.getElementById('color-preview');

            colorSelect.addEventListener('change', function() {
                colorPreview.style.backgroundColor = this.value;
            });

            // Update intro tab title in navigation when input changes
            const introTitleInput = document.getElementById('intro_title');
            const introTabNav = document.getElementById('intro-tab-nav');

            introTitleInput.addEventListener('input', function() {
                const newTitle = this.value.trim() || 'Introduktion';
                introTabNav.textContent = newTitle;
            });
        });

        // Update publish status text
        function updatePublishStatus(checkbox) {
            const statusText = document.getElementById('publish-status-text');
            const statusSubtext = statusText.parentElement.nextElementSibling;

            if (checkbox.checked) {
                statusText.textContent = 'Forløbet er publiceret';
                statusSubtext.textContent = 'Synligt for brugere';
            } else {
                statusText.textContent = 'Forløbet er ikke publiceret';
                statusSubtext.textContent = 'Kun synligt for dig';
            }
        }

        // Toggle between free and paid sections
        function togglePriceType(checkbox) {
            const priceTypeText = document.getElementById('price-type-text');
            const paidSection = document.getElementById('paid-section');
            const freeSection = document.getElementById('free-section');
            const isFreeInput = document.getElementById('is_free');

            if (checkbox.checked) {
                // Switch ON = Paid (koster penge)
                priceTypeText.textContent = 'Forløbet koster penge';
                paidSection.style.display = 'block';
                freeSection.style.display = 'none';
                isFreeInput.value = '0';
            } else {
                // Switch OFF = Free (gratis)
                priceTypeText.textContent = 'Forløbet er gratis';
                paidSection.style.display = 'none';
                freeSection.style.display = 'block';
                isFreeInput.value = '1';
            }
        }

        // Preview image before upload
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Clear image upload
        function clearImageUpload() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').style.display = 'none';
        }

        // Remove current image
        function removeImage() {
            if (confirm('Er du sikker på, at du vil fjerne dette billede?')) {
                // Create a temporary form just for removing the image
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PATCH');
                formData.append('remove_image', '1');
                formData.append('title', document.getElementById('title').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('intro_title', document.getElementById('intro_title').value);
                formData.append('mailing_list_id', document.getElementById('mailing_list_id').value);
                formData.append('price', document.getElementById('price').value);
                formData.append('primary_color', document.getElementById('primary_color').value);

                if (document.getElementById('is_free').checked) {
                    formData.append('is_free', '1');
                }
                if (document.getElementById('is_published').checked) {
                    formData.append('is_published', '1');
                }

                // Submit via fetch
                fetch('{{ route('creator.courses.update', $course) }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        alert('Der opstod en fejl. Prøv igen.');
                    }
                })
                .catch(error => {
                    alert('Der opstod en fejl. Prøv igen.');
                });
            }
        }

        // Submit new tab form via AJAX
        function submitTabForm() {
            const title = document.getElementById('tab_title').value;
            const content = document.getElementById('tab_content').value;

            if (!title || !content) {
                alert('Udfyld venligst både titel og indhold');
                return;
            }

            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('title', title);
            formData.append('content', content);

            // Submit via fetch
            fetch('{{ route('creator.courses.tabs.store', $course) }}', {
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

            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            formData.append('title', title);
            formData.append('content', content);

            // Submit via fetch
            fetch(`/creator/courses/{{ $course->id }}/tabs/${tabId}`, {
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

            fetch(`/creator/courses/{{ $course->id }}/tabs/${tabId}`, {
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
