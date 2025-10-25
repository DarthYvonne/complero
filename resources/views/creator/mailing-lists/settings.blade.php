<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Grupper</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Indstillinger</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                Gruppe: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
            </h1>
        </div>

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-circle-user me-1"></i> Medlemmer
                </a>
            </li>
            <li class="nav-item dropdown" role="presentation">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-plus me-1"></i> Sign up
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                        <i class="fa-solid fa-code me-2"></i> Forms
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                        <i class="fa-solid fa-qrcode me-2"></i> QR Code
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.landing-page', $mailingList) }}">
                        <i class="fa-solid fa-image me-2"></i> Landing page
                    </a></li>
                </ul>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.welcome', $mailingList) }}">
                    <i class="fa-solid fa-heart me-1"></i> Velkomst
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.content', $mailingList) }}">
                    <i class="fa-solid fa-circle-play me-1"></i> Indhold
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.settings', $mailingList) }}">
                    <i class="fa-solid fa-gear me-1"></i> Indstillinger
                </a>
            </li>
        </ul>

        <!-- Page Description -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">
                    <i class="fa-solid fa-gear me-2" style="color: var(--primary-color);"></i>
                    Indstillinger for gruppen
                </h2>
                <button type="submit" form="settings-form" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-save me-1"></i> Gem indstillinger
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Settings Form -->
        <form id="settings-form" action="{{ route('creator.mailing-lists.update-settings', $mailingList) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="row g-4">
                <!-- Group Information -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                Gruppens navn
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label" style="font-weight: 500;">
                                    Navn <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $mailingList->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label for="description" class="form-label" style="font-weight: 500;">
                                    Beskrivelse
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4">{{ old('description', $mailingList->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organization Information -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                Om udbyderen
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="organization_name" class="form-label" style="font-weight: 500;">
                                    Navn på organisation/firma/community
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="organization_name"
                                       name="organization_name"
                                       value="{{ old('organization_name', $mailingList->organization_name) }}"
                                       placeholder="F.eks. Min Organisation">
                            </div>

                            <div class="mb-3">
                                <label for="website" class="form-label" style="font-weight: 500;">
                                    Webadresse
                                </label>
                                <input type="url"
                                       class="form-control"
                                       id="website"
                                       name="website"
                                       value="{{ old('website', $mailingList->website) }}"
                                       placeholder="https://example.com">
                            </div>

                            <div class="mb-3">
                                <label for="responsible_person" class="form-label" style="font-weight: 500;">
                                    Ansvarlig person
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="responsible_person"
                                       name="responsible_person"
                                       value="{{ old('responsible_person', $mailingList->responsible_person) }}"
                                       placeholder="F.eks. Anders Jensen">
                            </div>

                            <div class="mb-0">
                                <label for="support_email" class="form-label" style="font-weight: 500;">
                                    Support email
                                </label>
                                <input type="email"
                                       class="form-control"
                                       id="support_email"
                                       name="support_email"
                                       value="{{ old('support_email', $mailingList->support_email) }}"
                                       placeholder="support@example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Delete Group -->
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-danger" onclick="deleteGroup()">
                            <i class="fa-solid fa-trash me-1"></i> Slet gruppen
                        </button>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Status -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="font-size: 16px; font-weight: 600; color: #333;">Status</strong>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="statusToggle"
                                           {{ $mailingList->is_active ? 'checked' : '' }}
                                           onchange="toggleStatus(this)">
                                    <label class="form-check-label" for="statusToggle" style="font-weight: 500; color: #666;">
                                        <span id="statusLabel">{{ $mailingList->is_active ? 'Aktiv' : 'Inaktiv' }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Color Scheme -->
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-palette" style="color: var(--primary-color);"></i> Farveskema
                            </h5>
                        </div>
                        <div class="card-body">
                            <p style="font-weight: 300; color: #666; margin-bottom: 20px;">
                                Disse farver bruges til alle forløb og indhold i denne gruppe.
                            </p>

                            <div class="mb-3">
                                <label for="primary_color" class="form-label" style="font-weight: 500;">
                                    Primær farve
                                </label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="color"
                                           class="form-control form-control-color"
                                           id="primary_color"
                                           name="primary_color"
                                           value="{{ old('primary_color', $mailingList->primary_color ?? '#be185d') }}"
                                           style="width: 60px; height: 40px;">
                                    <input type="text"
                                           class="form-control"
                                           value="{{ old('primary_color', $mailingList->primary_color ?? '#be185d') }}"
                                           readonly
                                           style="font-family: monospace;">
                                </div>
                            </div>
                            <input type="hidden" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $mailingList->secondary_color ?? '#9d174d') }}">

                            <!-- Color Preview -->
                            <div class="mt-4">
                                <label class="form-label" style="font-weight: 500;">Forhåndsvisning</label>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                            class="btn"
                                            id="colorPreview"
                                            style="background: {{ $mailingList->primary_color ?? '#be185d' }}; border-color: {{ $mailingList->primary_color ?? '#be185d' }}; color: white;">
                                        Eksempel knap
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: none;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
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
        .form-control {
            border-radius: 6px;
        }

        /* Dropdown on hover */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin-top: 0;
        }
    </style>

    <script>
        // Function to calculate darker secondary color
        function calculateSecondaryColor(hex) {
            // Convert hex to RGB
            const r = parseInt(hex.substring(1, 3), 16);
            const g = parseInt(hex.substring(3, 5), 16);
            const b = parseInt(hex.substring(5, 7), 16);

            // Darken by 15%
            const darkenFactor = 0.85;
            const newR = Math.floor(r * darkenFactor);
            const newG = Math.floor(g * darkenFactor);
            const newB = Math.floor(b * darkenFactor);

            // Convert back to hex
            return '#' +
                newR.toString(16).padStart(2, '0') +
                newG.toString(16).padStart(2, '0') +
                newB.toString(16).padStart(2, '0');
        }

        // Update color preview and calculate secondary color
        document.getElementById('primary_color').addEventListener('input', function(e) {
            const color = e.target.value;
            const secondaryColor = calculateSecondaryColor(color);

            document.getElementById('colorPreview').style.background = color;
            document.getElementById('colorPreview').style.borderColor = color;
            e.target.nextElementSibling.value = color;
            document.getElementById('secondary_color').value = secondaryColor;
        });

        // Toggle status
        function toggleStatus(checkbox) {
            const isActive = checkbox.checked;
            const label = document.getElementById('statusLabel');

            fetch('{{ route('creator.mailing-lists.update', $mailingList) }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                label.textContent = isActive ? 'Aktiv' : 'Inaktiv';
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !isActive;
            });
        }

        // Delete group
        function deleteGroup() {
            if (confirm('Er du sikker på at du vil slette denne gruppe? Dette kan ikke fortrydes.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('creator.mailing-lists.destroy', $mailingList) }}';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
