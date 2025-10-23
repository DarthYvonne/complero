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

        <!-- Page Description and Embed Code -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 style="font-size: 20px; font-weight: 600; color: #333;">
                Vælg en signup form til din hjemmeside
            </h2>
            <div class="input-group" style="max-width: 600px;">
                <input type="text"
                       class="form-control"
                       id="embedCodeInput"
                       value='&lt;iframe src="{{ url('/signup/' . $mailingList->slug) }}" width="100%" height="600" frameborder="0" style="border: 1px solid #e0e0e0; border-radius: 8px;"&gt;&lt;/iframe&gt;'
                       readonly
                       style="font-family: monospace; font-size: 12px;">
                <button class="btn btn-outline-primary" type="button" onclick="copyEmbedCode()">
                    <i class="fa-solid fa-copy me-1"></i> Kopier embed code
                </button>
            </div>
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
                            <h5 class="mb-1" style="font-size: 16px; font-weight: 600; color: #333;">Simpel</h5>
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
                            <h5 class="mb-1" style="font-size: 16px; font-weight: 600; color: #333;">Banner</h5>
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

            <!-- Template 4: Raw HTML -->
            <div class="col-lg-4">
                <div class="card template-card h-100" id="card-raw" style="cursor: pointer; border: 2px solid #e0e0e0; transition: border-color 0.2s;">
                    <div class="card-body p-0">
                        <div class="template-preview" style="background: #f8f9fa; padding: 30px; min-height: 400px;">
                            <div style="background: white; border-radius: 8px; padding: 30px; max-width: 400px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 22px; font-weight: 600; color: #333; margin-bottom: 10px;" id="raw-preview-header">
                                    Tilmeld dig vores liste
                                </h3>
                                <p style="font-size: 14px; color: #666; margin-bottom: 20px;" id="raw-preview-body">
                                    Få eksklusive opdateringer og indhold direkte i din indbakke.
                                </p>
                                <form>
                                    <input type="text" placeholder="Dit navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                    <input type="email" placeholder="Din email" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                    <button type="submit" style="width: 100%; padding: 12px; background: #B9185E; color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;" id="raw-preview-button">
                                        Tilmeld
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-center py-3">
                            <h5 class="mb-1" style="font-size: 16px; font-weight: 600; color: #333;">Ren HTML</h5>
                            <p class="mb-2 small text-muted" style="font-weight: 300;">Generer HTML-kode til din egen styling</p>
                            <div class="template-actions" style="font-size: 13px;">
                                <a href="#" onclick="openCustomizer('raw'); return false;" style="color: #666; text-decoration: none;">Rediger</a>
                                <span style="color: #ccc; margin: 0 8px;">|</span>
                                <a href="#" onclick="selectTemplate('raw'); return false;" class="select-link" id="select-raw" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Vælg</a>
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
                        Tilpas signup form: <span id="modal-template-name" style="font-weight: 100;"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Customization Options -->
                        <div class="col-lg-5">
                            <h6 class="mb-3" style="font-weight: 600;">Indstillinger</h6>

                            <!-- Image Upload -->
                            <div class="mb-3" id="imageUploadSection">
                                <div class="upload-zone" id="modal-upload-zone" onclick="document.getElementById('imageUpload').click()" style="cursor: pointer; border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; background: #f8fafc; transition: all 0.3s ease;">
                                    <input type="file"
                                           class="d-none"
                                           id="imageUpload"
                                           accept="image/*"
                                           onchange="uploadImage()">
                                    <input type="hidden" id="customImageUrl">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    <p class="mb-0 mt-2" style="font-weight: 500; color: #64748b; font-size: 14px;" id="uploadText">Klik for at uploade billede</p>
                                    <p class="small text-muted mb-0">Max 2MB. JPG, PNG eller WebP</p>
                                </div>
                            </div>

                            <!-- Header Text -->
                            <div class="mb-3">
                                <input type="text"
                                    class="form-control"
                                    id="customHeader"
                                    placeholder="Overskrift"
                                    style="font-weight: bold;"
                                    oninput="updatePreview()">
                            </div>

                            <!-- Body Text -->
                            <div class="mb-3">
                                <textarea class="form-control"
                                    id="customBody"
                                    rows="3"
                                    placeholder="Brødtekst"
                                    oninput="updatePreview()"></textarea>
                            </div>

                            <!-- Offer Membership -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="offerMembership"
                                           {{ $mailingList->offer_membership ? 'checked' : '' }}
                                           onchange="toggleOfferMembership(this)">
                                    <label class="form-check-label" for="offerMembership" style="font-weight: 500;">
                                        Tilbyd medlemsskab
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1" style="margin-left: 24px;">
                                    Vis et password-felt hvis brugerne skal kunne logge ind og hente indhold
                                </small>
                            </div>

                            <!-- Button Text -->
                            <div class="mb-3">
                                <input type="text"
                                    class="form-control"
                                    id="customButtonText"
                                    placeholder="Tilmeld"
                                    oninput="updatePreview(); updateButtonTextBorder()">
                            </div>

                            <!-- Button Color -->
                            <div class="mb-0" id="buttonColorSection">
                                <label for="customButtonColor" class="form-label" style="font-weight: 500; font-size: 14px;">Knap farve</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color"
                                        class="form-control form-control-color"
                                        id="customButtonColor"
                                        title="Vælg knap farve"
                                        oninput="updatePreview(); updateButtonTextBorder()"
                                        style="width: 60px; height: 38px;">
                                    <input type="text"
                                        class="form-control"
                                        id="customButtonColorHex"
                                        placeholder="#B9185E"
                                        maxlength="7"
                                        oninput="updateButtonColorFromHex(this.value)"
                                        style="font-family: monospace; flex: 1;">
                                </div>
                            </div>

                        </div>

                        <!-- Live Preview -->
                        <div class="col-lg-7">
                            <h6 class="mb-3" style="font-weight: 600;" id="previewHeading">Forhåndsvisning</h6>
                            <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0; min-height: 500px;">
                                <div id="livePreview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link {
            background-color: transparent !important;
        }

        .nav-tabs .nav-link.active {
            background-color: transparent !important;
        }

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

        #modal-upload-zone:hover {
            border-color: var(--primary-color);
            background: #f1f5f9;
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
                'body' => 'Få eksklusive opdateringer og indhold direkte i din indbakke.',
                'buttonText' => 'Tilmeld',
                'buttonColor' => '#B9185E'
            ],
            'modern' => [
                'image' => asset('graphics/header-placeholder.jpg'),
                'header' => 'Bliv en del af vores community',
                'body' => 'Tilmeld dig og få adgang til eksklusivt indhold og ressourcer.',
                'buttonText' => 'Start nu',
                'buttonColor' => '#B9185E'
            ],
            'split' => [
                'image' => asset('graphics/side-placeholder.jpg'),
                'header' => 'Tilmeld dig i dag',
                'body' => 'Få de nyeste nyheder og opdateringer.',
                'buttonText' => 'Tilmeld',
                'buttonColor' => '#B9185E'
            ],
            'raw' => [
                'image' => '',
                'header' => 'Tilmeld dig vores liste',
                'body' => 'Få eksklusive opdateringer og indhold direkte i din indbakke.',
                'buttonText' => 'Tilmeld',
                'buttonColor' => ''
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
                    if (template !== 'raw') {
                        document.getElementById(`${template}-preview-image`).src = data.image || '';
                    }
                    document.getElementById(`${template}-preview-header`).textContent = data.header || '';
                    document.getElementById(`${template}-preview-body`).textContent = data.body || '';
                    if (template === 'raw') {
                        document.getElementById(`${template}-preview-button`).textContent = data.buttonText || 'Tilmeld';
                    }
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

            const uploadText = document.getElementById('uploadText');
            uploadText.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploader...';

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
                      uploadText.textContent = 'Billede uploadet!';
                      setTimeout(() => {
                          uploadText.textContent = 'Klik for at uploade billede';
                      }, 2000);
                      updatePreview();
                      saveCustomization();
                  } else {
                      uploadText.textContent = 'Fejl! Prøv igen';
                      setTimeout(() => {
                          uploadText.textContent = 'Klik for at uploade billede';
                      }, 2000);
                  }
              }).catch(err => {
                  uploadText.textContent = 'Fejl! Prøv igen';
                  setTimeout(() => {
                      uploadText.textContent = 'Klik for at uploade billede';
                  }, 2000);
              });
        }

        function saveCustomization() {
            const image = document.getElementById('customImageUrl').value;
            const header = document.getElementById('customHeader').value;
            const body = document.getElementById('customBody').value;
            const buttonText = document.getElementById('customButtonText').value;
            const buttonColor = document.getElementById('customButtonColor').value;

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
                    body: body,
                    buttonText: buttonText,
                    buttonColor: buttonColor
                })
            });
        }

        function openCustomizer(template) {
            currentTemplate = template;

            // Update modal title
            const templateNames = {
                simple: 'Simpel',
                modern: 'Banner',
                split: 'Split',
                raw: 'Ren HTML'
            };
            document.getElementById('modal-template-name').textContent = templateNames[template];

            // Ensure template data exists, use defaults if not
            if (!customData[template]) {
                const defaults = {
                    'simple': {
                        'image': '{{ asset('graphics/logo.png') }}',
                        'header': 'Tilmeld dig vores liste',
                        'body': 'Få eksklusive opdateringer og indhold direkte i din indbakke.',
                        'buttonText': 'Tilmeld',
                        'buttonColor': '#B9185E'
                    },
                    'modern': {
                        'image': '{{ asset('graphics/header-placeholder.jpg') }}',
                        'header': 'Bliv en del af vores community',
                        'body': 'Tilmeld dig og få adgang til eksklusivt indhold og ressourcer.',
                        'buttonText': 'Start nu',
                        'buttonColor': '#B9185E'
                    },
                    'split': {
                        'image': '{{ asset('graphics/side-placeholder.jpg') }}',
                        'header': 'Tilmeld dig i dag',
                        'body': 'Få de nyeste nyheder og opdateringer.',
                        'buttonText': 'Tilmeld',
                        'buttonColor': '#B9185E'
                    },
                    'raw': {
                        'image': '',
                        'header': 'Tilmeld dig vores liste',
                        'body': 'Få eksklusive opdateringer og indhold direkte i din indbakke.',
                        'buttonText': 'Tilmeld',
                        'buttonColor': ''
                    }
                };
                customData[template] = defaults[template];
            }

            // Show/hide sections based on template
            if (template === 'raw') {
                document.getElementById('imageUploadSection').style.display = 'none';
                document.getElementById('buttonColorSection').style.display = 'none';
                document.getElementById('previewHeading').textContent = 'Kopier denne HTML til din side';
            } else {
                document.getElementById('imageUploadSection').style.display = 'block';
                document.getElementById('buttonColorSection').style.display = 'block';
                document.getElementById('previewHeading').textContent = 'Forhåndsvisning';
            }

            // Load current values
            document.getElementById('customImageUrl').value = customData[template].image || '';
            document.getElementById('customHeader').value = customData[template].header || '';
            document.getElementById('customBody').value = customData[template].body || '';
            document.getElementById('customButtonText').value = customData[template].buttonText || '';
            document.getElementById('customButtonColor').value = customData[template].buttonColor || '#B9185E';
            document.getElementById('customButtonColorHex').value = customData[template].buttonColor || '#B9185E';

            // Update preview and button border
            updatePreview();
            if (template !== 'raw') {
                updateButtonTextBorder();
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('customizerModal'));
            modal.show();
        }

        function updatePreview() {
            const image = document.getElementById('customImageUrl').value || customData[currentTemplate].image;
            const header = document.getElementById('customHeader').value || customData[currentTemplate].header;
            const body = document.getElementById('customBody').value || customData[currentTemplate].body;
            const buttonText = document.getElementById('customButtonText').value || customData[currentTemplate].buttonText;
            const buttonColor = document.getElementById('customButtonColor').value || customData[currentTemplate].buttonColor || '#B9185E';

            // Sync color picker and hex input
            document.getElementById('customButtonColor').value = buttonColor;
            document.getElementById('customButtonColorHex').value = buttonColor;

            // Update stored data
            customData[currentTemplate] = {
                image,
                header,
                body,
                buttonText,
                buttonColor
            };

            // Update small preview
            if (currentTemplate !== 'raw') {
                document.getElementById(`${currentTemplate}-preview-image`).src = image;
            }
            document.getElementById(`${currentTemplate}-preview-header`).textContent = header;
            document.getElementById(`${currentTemplate}-preview-body`).textContent = body;
            if (currentTemplate === 'raw') {
                document.getElementById(`${currentTemplate}-preview-button`).textContent = buttonText;
            }

            // Auto-save to database (debounced)
            clearTimeout(window.saveTimeout);
            window.saveTimeout = setTimeout(() => {
                saveCustomization();
            }, 1000);

            // Update live preview in modal
            const livePreview = document.getElementById('livePreview');

            const offerMembership = document.getElementById('offerMembership').checked;
            const passwordField = offerMembership ? '<input type="password" placeholder="Vælg et password" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px;">' : '';

            if (currentTemplate === 'raw') {
                // Generate raw HTML code
                const passwordFieldRaw = offerMembership ? '\n    <input type="password" name="password" placeholder="Vælg et password" required>' : '';
                const rawHTML = `<form action="https://complicero.com/signup/{{ $mailingList->slug }}" method="POST">
    <h3>${header}</h3>
    <p>${body}</p>
    <input type="text" name="name" placeholder="Dit navn" required>
    <input type="email" name="email" placeholder="Din email" required>${passwordFieldRaw}
    <button type="submit">${buttonText}</button>
</form>`;

                livePreview.innerHTML = `<pre style="background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #dee2e6; overflow-x: auto; max-height: 500px; font-size: 13px; margin: 0; white-space: pre-wrap; word-wrap: break-word;"><code>${escapeHtml(rawHTML)}</code></pre>`;
            } else if (currentTemplate === 'simple') {
                livePreview.innerHTML = `
                    <div style="text-align: center; max-width: 400px; margin: 0 auto;">
                        <div style="text-align: center; margin-bottom: 30px;">
                            <img src="${image}" alt="Logo" style="width: auto; height: auto; max-width: 100%; display: inline-block; margin-bottom: 20px;">
                        </div>
                        <h3 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 10px;">${header}</h3>
                        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">${body}</p>
                        <form style="text-align: left;">
                            <input type="text" placeholder="Dit navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <input type="email" placeholder="Din email" style="width: 100%; padding: 10px; margin-bottom: ${offerMembership ? '10px' : '15px'}; border: 1px solid #ddd; border-radius: 4px;">
                            ${passwordField}
                            <button type="submit" style="width: 100%; padding: 12px; background: ${buttonColor}; color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">${buttonText}</button>
                        </form>
                    </div>
                `;
            } else if (currentTemplate === 'modern') {
                const passwordFieldModern = offerMembership ? '<input type="password" placeholder="Vælg et password" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">' : '';
                livePreview.innerHTML = `
                    <div style="background: white; border-radius: 8px; overflow: hidden; max-width: 400px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <img src="${image}" alt="Header" style="width: 100%; height: 150px; object-fit: cover;">
                        <div style="padding: 25px;">
                            <h3 style="font-size: 22px; font-weight: 600; color: #333; margin-bottom: 10px;">${header}</h3>
                            <p style="font-size: 14px; color: #666; margin-bottom: 20px;">${body}</p>
                            <form>
                                <input type="text" placeholder="Navn" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e0e0e0; border-radius: 4px;">
                                <input type="email" placeholder="Email" style="width: 100%; padding: 10px; margin-bottom: ${offerMembership ? '10px' : '15px'}; border: 1px solid #e0e0e0; border-radius: 4px;">
                                ${passwordFieldModern}
                                <button type="submit" style="width: 100%; padding: 12px; background: ${buttonColor}; color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">${buttonText}</button>
                            </form>
                        </div>
                    </div>
                `;
            } else if (currentTemplate === 'split') {
                const passwordFieldSplit = offerMembership ? '<input type="password" placeholder="Vælg et password" style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px;">' : '';
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
                                <input type="email" placeholder="Email" style="width: 100%; padding: 10px; margin-bottom: ${offerMembership ? '10px' : '15px'}; border: 1px solid #e0e0e0; border-radius: 4px;">
                                ${passwordFieldSplit}
                                <button type="submit" style="width: 100%; padding: 12px; background: ${buttonColor}; color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">${buttonText}</button>
                            </form>
                        </div>
                    </div>
                `;
            }
        }

        function updateButtonColorFromHex(hexColor) {
            // Validate hex color format
            if (/^#[0-9A-F]{6}$/i.test(hexColor)) {
                document.getElementById('customButtonColor').value = hexColor;
                updatePreview();
                updateButtonTextBorder();
            }
        }

        function updateButtonTextBorder() {
            const buttonColor = document.getElementById('customButtonColor').value || '#B9185E';
            document.getElementById('customButtonText').style.border = `2px solid ${buttonColor}`;
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function copyEmbedCode() {
            const input = document.getElementById('embedCodeInput');
            input.select();

            navigator.clipboard.writeText(input.value).then(() => {
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

        function toggleOfferMembership(checkbox) {
            // Save setting to database
            fetch('{{ route('creator.mailing-lists.signup-form.offer-membership', $mailingList) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    offer_membership: checkbox.checked
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      // Update preview
                      updatePreview();
                  }
              });
        }
    </script>
</x-app-layout>