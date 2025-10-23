<x-app-layout>
    @section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Signup forms</strong>
    @endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                        Signup forms: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('creator.mailing-lists.edit', $mailingList) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen me-1"></i> Rediger mailingliste
                    </a>
                    <a href="{{ route('creator.mailing-lists.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                    </a>
                </div>
            </div>
        </div>

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-list me-1"></i> Mailingliste
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                    <i class="fa-solid fa-code me-1"></i> Signup forms
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                    <i class="fa-solid fa-qrcode me-1"></i> QR Kode
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.import', $mailingList) }}">
                    <i class="fa-solid fa-file-import me-1"></i> Importer
                </a>
            </li>
        </ul>

        <!-- Page Description -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333;">
                Vælg en signup form til din hjemmeside
            </h2>
        </div>

        <!-- Form Templates -->
        <div class="row g-4 mb-4">
            <!-- Template 1: Simple/Minimal -->
            <div class="col-lg-4">
                <div class="card template-card h-100" id="card-simple" style="cursor: pointer; border: 2px solid #e0e0e0; transition: border-color 0.2s;">
                    <div class="card-body p-0">
                        <div class="template-preview" style="background: #f8f9fa; padding: 30px; min-height: 400px;">
                            <div style="text-align: center; max-width: 400px; margin: 0 auto;">
                                <div class="mb-4" style="text-align: center;">
                                    <img src="{{ asset('graphics/logo.png') }}"
                                        alt="Logo"
                                        style="width: auto; height: auto; max-width: 100%; display: inline-block; margin-bottom: 20px;"
                                        id="simple-preview-image">
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;" id="simple-preview-header">
                                    Tilmeld dig vores liste
                                </h3>
                                <p style="font-size: 14px; color: #666; margin-bottom: 20px;" id="simple-preview-body">
                                    Få eksklusive opdateringer og indhold direkte i din indbakke.
                                </p>
                                <form style="text-align: left;">
                                    <input type="text" placeholder="Dit navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                    <input type="email" placeholder="Din email" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
                                    <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                                        Tilmeld
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-center py-3">
                            <h5 class="mb-1" style="font-size: 16px; font-weight: 600; color: #333;">Simple</h5>
                            <p class="mb-2 small text-muted" style="font-weight: 300;">Minimalistisk og centreret design</p>
                            <div class="template-actions" style="font-size: 13px;">
                                <a href="#" onclick="openCustomizer('simple'); return false;" style="color: #666; text-decoration: none;">Rediger</a>
                                <span style="color: #ccc; margin: 0 8px;">|</span>
                                <a href="#" onclick="selectTemplate('simple'); return false;" class="select-link" id="select-simple" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Vælg</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template 2: Modern/Card -->
            <div class="col-lg-4">
                <div class="card template-card h-100" id="card-modern" style="cursor: pointer; border: 2px solid #e0e0e0; transition: border-color 0.2s;">
                    <div class="card-body p-0">
                        <div class="template-preview" style="background: #f8f9fa; padding: 30px; min-height: 400px;">
                            <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 400px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <img src="{{ asset('graphics/header-placeholder.jpg') }}"
                                    alt="Header"
                                    style="width: 100%; height: 150px; object-fit: cover;"
                                    id="modern-preview-image">
                                <div style="padding: 25px;">
                                    <h3 style="font-size: 22px; font-weight: 600; color: #333; margin-bottom: 10px;" id="modern-preview-header">
                                        Bliv en del af vores community
                                    </h3>
                                    <p style="font-size: 14px; color: #666; margin-bottom: 20px;" id="modern-preview-body">
                                        Tilmeld dig og få adgang til eksklusivt indhold og ressourcer.
                                    </p>
                                    <form>
                                        <input type="text" placeholder="Navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                        <input type="email" placeholder="Email" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                        <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                                            Start nu
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-center py-3">
                            <h5 class="mb-1" style="font-size: 16px; font-weight: 600; color: #333;">Modern</h5>
                            <p class="mb-2 small text-muted" style="font-weight: 300;">Kort med header billede</p>
                            <div class="template-actions" style="font-size: 13px;">
                                <a href="#" onclick="openCustomizer('modern'); return false;" style="color: #666; text-decoration: none;">Rediger</a>
                                <span style="color: #ccc; margin: 0 8px;">|</span>
                                <a href="#" onclick="selectTemplate('modern'); return false;" class="select-link" id="select-modern" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Vælg</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template 3: Split/Hero -->
            <div class="col-lg-4">
                <div class="card template-card h-100" id="card-split" style="cursor: pointer; border: 2px solid #e0e0e0; transition: border-color 0.2s;">
                    <div class="card-body p-0">
                        <div class="template-preview" style="background: #f8f9fa; padding: 30px; min-height: 400px;">
                            <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 500px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; align-items: stretch; min-height: 340px;">
                                <div style="flex: 1; background: var(--primary-color); position: relative;">
                                    <img src="{{ asset('graphics/side-placeholder.jpg') }}"
                                        alt="Side"
                                        style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"
                                        id="split-preview-image">
                                </div>
                                <div style="flex: 1; padding: 30px; display: flex; flex-direction: column; justify-content: center;">
                                    <h3 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 10px;" id="split-preview-header">
                                        Tilmeld dig i dag
                                    </h3>
                                    <p style="font-size: 13px; color: #666; margin-bottom: 20px;" id="split-preview-body">
                                        Få de nyeste nyheder og opdateringer.
                                    </p>
                                    <form>
                                        <input type="text" placeholder="Navn" style="width: 100%; padding: 8px; margin-bottom: 8px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;">
                                        <input type="email" placeholder="Email" style="width: 100%; padding: 8px; margin-bottom: 12px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;">
                                        <button type="submit" style="width: 100%; padding: 10px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer; font-size: 13px;">
                                            Tilmeld
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-center py-3">
                            <h5 class="mb-1" style="font-size: 16px; font-weight: 600; color: #333;">Split</h5>
                            <p class="mb-2 small text-muted" style="font-weight: 300;">To-kolonner med side billede</p>
                            <div class="template-actions" style="font-size: 13px;">
                                <a href="#" onclick="openCustomizer('split'); return false;" style="color: #666; text-decoration: none;">Rediger</a>
                                <span style="color: #ccc; margin: 0 8px;">|</span>
                                <a href="#" onclick="selectTemplate('split'); return false;" class="select-link" id="select-split" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Vælg</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customization Modal -->
    <div class="modal fade" id="customizerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-sliders me-2"></i>
                        Tilpas tilmeldingsformular: <span id="modal-template-name"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Customization Options -->
                        <div class="col-lg-5">
                            <h6 class="mb-3" style="font-weight: 600;">Indstillinger</h6>

                            <!-- Image Upload -->
                            <div class="mb-3">
                                <label for="customImageUrl" class="form-label" style="font-weight: 500;">
                                    <i class="fa-solid fa-image me-1"></i> Image
                                </label>
                                <div class="input-group">
                                    <input type="file"
                                           class="form-control"
                                           id="imageUpload"
                                           accept="image/*"
                                           onchange="uploadImage()">
                                    <button class="btn btn-outline-secondary" type="button" id="uploadButton">
                                        <i class="fa-solid fa-upload me-1"></i> Upload
                                    </button>
                                </div>
                                <input type="hidden" id="customImageUrl">
                            </div>

                            <!-- Header Text -->
                            <div class="mb-3">
                                <label for="customHeader" class="form-label" style="font-weight: 500;">
                                    <i class="fa-solid fa-heading me-1"></i> Overskrift
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="customHeader"
                                    placeholder="Din overskrift her"
                                    oninput="updatePreview()">
                            </div>

                            <!-- Body Text -->
                            <div class="mb-3">
                                <label for="customBody" class="form-label" style="font-weight: 500;">
                                    <i class="fa-solid fa-align-left me-1"></i> Brødtekst
                                </label>
                                <textarea class="form-control"
                                    id="customBody"
                                    rows="3"
                                    placeholder="Din beskrivelse her"
                                    oninput="updatePreview()"></textarea>
                            </div>

                            <!-- Embed Code -->
                            <hr class="my-4">
                            <h6 class="mb-2" style="font-weight: 600;">
                                <i class="fa-solid fa-code me-1"></i> Embed kode
                            </h6>
                            <p class="small text-muted mb-2" style="font-weight: 300;">
                                Denne kode viser altid den formular du har valgt. Du kan skifte formular uden at skulle opdatere koden.
                            </p>
                            <div class="position-relative">
                                <pre style="background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid #dee2e6; overflow-x: auto; max-height: 150px; font-size: 12px; margin-bottom: 0;"><code id="embedCode">&lt;iframe src="{{ url('/signup/' . $mailingList->slug) }}" width="100%" height="600" frameborder="0" style="border: 1px solid #e0e0e0; border-radius: 8px;"&gt;&lt;/iframe&gt;</code></pre>
                                <button class="btn btn-sm btn-outline-primary position-absolute" style="top: 8px; right: 8px; font-size: 12px; padding: 4px 10px;" onclick="copyModalEmbedCode()">
                                    <i class="fa-solid fa-copy me-1"></i> Kopier
                                </button>
                            </div>

                        </div>

                        <!-- Live Preview -->
                        <div class="col-lg-7">
                            <h6 class="mb-3" style="font-weight: 600;">Forhåndsvisning</h6>
                            <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 500px;">
                                <div id="livePreview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Luk</button>
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

        .template-card {
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }

        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .template-card.selected {
            border-color: #B9185E !important;
        }
    </style>

    <script>
        let currentTemplate = 'simple';
        let selectedTemplate = '{{ $selectedTemplate }}';

        // Load saved form data or use defaults
        @php
        $defaults = [
            'simple' => [
                'image' => asset('graphics/logo.png'),
                'header' => 'Tilmeld dig vores liste',
                'body' => 'Få eksklusive opdateringer og indhold direkte i din indbakke.'
            ],
            'modern' => [
                'image' => asset('graphics/header-placeholder.jpg'),
                'header' => 'Bliv en del af vores community',
                'body' => 'Tilmeld dig og få adgang til eksklusivt indhold og ressourcer.'
            ],
            'split' => [
                'image' => asset('graphics/side-placeholder.jpg'),
                'header' => 'Tilmeld dig i dag',
                'body' => 'Få de nyeste nyheder og opdateringer.'
            ]
        ];
        $formDataToUse = !empty($formData) ? $formData : $defaults;
        @endphp
        let customData = @json($formDataToUse);

        // Initialize - mark the saved template as selected
        document.addEventListener('DOMContentLoaded', function() {
            // Load previews with saved data
            Object.keys(customData).forEach(template => {
                if (customData[template]) {
                    const data = customData[template];
                    document.getElementById(`${template}-preview-image`).src = data.image || '';
                    document.getElementById(`${template}-preview-header`).textContent = data.header || '';
                    document.getElementById(`${template}-preview-body`).textContent = data.body || '';
                }
            });

            selectTemplate(selectedTemplate);
        });

        function selectTemplate(template) {
            // Update selected template
            selectedTemplate = template;

            // Save to database
            fetch('{{ route('creator.mailing-lists.signup-form.template', $mailingList) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ template: template })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      // Remove selected class from all cards
                      document.querySelectorAll('.template-card').forEach(card => {
                          card.classList.remove('selected');
                      });

                      // Add selected class to chosen card
                      document.getElementById('card-' + template).classList.add('selected');

                      // Update all select links to say "Vælg"
                      document.querySelectorAll('.select-link').forEach(link => {
                          link.textContent = 'Vælg';
                      });

                      // Update the selected template's link to say "Aktiv"
                      document.getElementById('select-' + template).textContent = 'Aktiv';
                  }
              });
        }

        function uploadImage() {
            const fileInput = document.getElementById('imageUpload');
            const file = fileInput.files[0];

            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            const uploadButton = document.getElementById('uploadButton');
            uploadButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading...';
            uploadButton.disabled = true;

            fetch('{{ route('creator.mailing-lists.signup-form.upload-image', $mailingList) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      document.getElementById('customImageUrl').value = data.url;
                      updatePreview();
                      saveCustomization();
                  }
                  uploadButton.innerHTML = '<i class="fa-solid fa-upload me-1"></i> Upload';
                  uploadButton.disabled = false;
              });
        }

        function saveCustomization() {
            const image = document.getElementById('customImageUrl').value;
            const header = document.getElementById('customHeader').value;
            const body = document.getElementById('customBody').value;

            fetch('{{ route('creator.mailing-lists.signup-form.data', $mailingList) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    template: currentTemplate,
                    image: image,
                    header: header,
                    body: body
                })
            });
        }

        function openCustomizer(template) {
            currentTemplate = template;

            // Update modal title
            const templateNames = {
                simple: 'Simple',
                modern: 'Modern',
                split: 'Split'
            };
            document.getElementById('modal-template-name').textContent = templateNames[template];

            // Load current values
            document.getElementById('customImageUrl').value = customData[template].image;
            document.getElementById('customHeader').value = customData[template].header;
            document.getElementById('customBody').value = customData[template].body;

            // Update preview
            updatePreview();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('customizerModal'));
            modal.show();
        }

        function updatePreview() {
            const image = document.getElementById('customImageUrl').value || customData[currentTemplate].image;
            const header = document.getElementById('customHeader').value || customData[currentTemplate].header;
            const body = document.getElementById('customBody').value || customData[currentTemplate].body;

            // Update stored data
            customData[currentTemplate] = {
                image,
                header,
                body
            };

            // Update small preview
            document.getElementById(`${currentTemplate}-preview-image`).src = image;
            document.getElementById(`${currentTemplate}-preview-header`).textContent = header;
            document.getElementById(`${currentTemplate}-preview-body`).textContent = body;

            // Auto-save to database (debounced)
            clearTimeout(window.saveTimeout);
            window.saveTimeout = setTimeout(() => {
                saveCustomization();
            }, 1000);

            // Update live preview in modal
            const livePreview = document.getElementById('livePreview');

            if (currentTemplate === 'simple') {
                livePreview.innerHTML = `
                    <div style="text-align: center; max-width: 400px; margin: 0 auto;">
                        <div style="text-align: center; margin-bottom: 30px;">
                            <img src="${image}" alt="Logo" style="width: auto; height: auto; max-width: 100%; display: inline-block; margin-bottom: 20px;">
                        </div>
                        <h3 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;">${header}</h3>
                        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">${body}</p>
                        <form style="text-align: left;">
                            <input type="text" placeholder="Dit navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <input type="email" placeholder="Din email" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">
                            <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">Tilmeld</button>
                        </form>
                    </div>
                `;
            } else if (currentTemplate === 'modern') {
                livePreview.innerHTML = `
                    <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 400px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <img src="${image}" alt="Header" style="width: 100%; height: 150px; object-fit: cover;">
                        <div style="padding: 25px;">
                            <h3 style="font-size: 22px; font-weight: 600; color: #333; margin-bottom: 10px;">${header}</h3>
                            <p style="font-size: 14px; color: #666; margin-bottom: 20px;">${body}</p>
                            <form>
                                <input type="text" placeholder="Navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                <input type="email" placeholder="Email" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">Start nu</button>
                            </form>
                        </div>
                    </div>
                `;
            } else if (currentTemplate === 'split') {
                livePreview.innerHTML = `
                    <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; align-items: stretch; min-height: 400px;">
                        <div style="flex: 1; background: var(--primary-color); position: relative;">
                            <img src="${image}" alt="Side" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                        </div>
                        <div style="flex: 1; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;">${header}</h3>
                            <p style="font-size: 14px; color: #666; margin-bottom: 20px;">${body}</p>
                            <form>
                                <input type="text" placeholder="Navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                <input type="email" placeholder="Email" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">Tilmeld</button>
                            </form>
                        </div>
                    </div>
                `;
            }
        }

        function copyModalEmbedCode() {
            const code = document.getElementById('embedCode').textContent;

            navigator.clipboard.writeText(code).then(() => {
                const button = event.target.closest('button');
                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="fa-solid fa-check me-1"></i> Kopieret!';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');

                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-primary');
                }, 2000);
            }).catch(err => {
                alert('Kunne ikke kopiere: ' + err);
            });
        }
    </script>
</x-app-layout>