<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Grupper</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $mailingList->name }}</strong>
@endsection

@php
    $effectiveRole = session('view_as', Auth::user()->role);
@endphp

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                Gruppe: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
            </h1>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Submenu Navigation -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
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
                <a class="nav-link" href="{{ route('creator.mailing-lists.settings', $mailingList) }}">
                    <i class="fa-solid fa-gear me-1"></i> Indstillinger
                </a>
            </li>
        </ul>

        <!-- Page Description -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333;">
                <i class="fa-solid fa-circle-user me-2" style="color: var(--primary-color);"></i>
                Medlemmer af gruppen
            </h2>
        </div>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-7">
                <!-- Members -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; color: #333;">
                            <b>{{ $mailingList->activeMembers->count() }}</b> medlemmer
                        </h5>
                        @if($mailingList->activeMembers->count() > 0)
                            <div style="width: 250px;">
                                <input type="text" id="memberSearch" class="form-control form-control-sm" placeholder="Søg medlemmer..." oninput="searchMembers()">
                            </div>
                        @endif
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @if($mailingList->activeMembers->count() > 0)
                            <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 600; color: #333;">Navn</th>
                                            <th style="font-weight: 600; color: #333;">Email</th>
                                            <th style="font-weight: 600; color: #333;">Tilmeldt</th>
                                            <th style="font-weight: 600; color: #333; width: 1%; white-space: nowrap;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mailingList->activeMembers as $member)
                                            <tr>
                                                <td style="font-weight: 500; white-space: nowrap;">{{ $member->name }}</td>
                                                <td style="font-weight: 300; color: #666; white-space: nowrap;">{{ $member->email }}</td>
                                                <td style="font-weight: 300; color: #666; white-space: nowrap;">
                                                    {{ $member->pivot->subscribed_at ? $member->pivot->subscribed_at->format('d/m/Y') : '-' }}
                                                </td>
                                                <td style="white-space: nowrap;">
                                                    <form action="{{ route('creator.mailing-lists.members.remove', [$mailingList, $member]) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('Er du sikker på, at du vil fjerne dette medlem?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fa-solid fa-trash"></i> Fjern
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-users" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen medlemmer endnu</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-5">
                <!-- Import Section -->
                <div class="card mb-3" id="uploadSection">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-file-import" style="color: var(--primary-color);"></i> Importer medlemmer
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf

                            <!-- File Upload Drop Zone -->
                            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('importFile').click()">
                                <input type="file"
                                       class="d-none"
                                       id="importFile"
                                       name="file"
                                       accept=".csv,.xlsx,.xls"
                                       required
                                       onchange="handleFileSelect(this)">
                                <div class="text-center py-3">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    <p class="mb-1 mt-2" style="font-weight: 500; color: #64748b; font-size: 14px;">
                                        Klik for at vælge fil
                                    </p>
                                    <p class="small text-muted mb-0">
                                        CSV eller Excel
                                    </p>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div id="file-preview" class="mt-3" style="display: none;">
                                <div class="alert alert-info d-flex justify-content-between align-items-center mb-0">
                                    <div>
                                        <i class="fa-solid fa-file me-2"></i>
                                        <span id="file-name"></span>
                                    </div>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="clearFileUpload()">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Field Mapping Section (Hidden initially) -->
                <div class="card mb-3" id="mappingSection" style="display: none;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-map" style="color: var(--primary-color);"></i> Kortlæg felter
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="mappingForm">
                            @csrf
                            <input type="hidden" id="fileData" name="fileData">

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 500; font-size: 14px;">
                                    Email kolonne <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-sm" id="emailField" name="email_field" required>
                                    <option value="">Vælg...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 500; font-size: 14px;">Navn kolonne</label>
                                <select class="form-select form-select-sm" id="nameField" name="name_field">
                                    <option value="">Vælg...</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skipDuplicates" name="skip_duplicates" checked>
                                    <label class="form-check-label" for="skipDuplicates" style="font-weight: 300; font-size: 14px;">
                                        Spring duplikater over
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetUpload()">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-check me-1"></i> Importér
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Button -->
                <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fa-solid fa-trash"></i> Slet liste
                </button>
            </div>
        </div>
    </div>

    <!-- Assign Courses Modal -->
    <div class="modal fade" id="assignCoursesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-circle-play me-2" style="color: var(--primary-color);"></i>
                        Tilbyd forløb til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-courses', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke forløb der skal være tilgængelige for medlemmer af denne liste.</p>

                        @if($availableCourses->count() > 0)
                            <div class="list-group">
                                @foreach($availableCourses as $course)
                                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor: pointer;">
                                        <input class="form-check-input mt-1" type="checkbox" name="course_ids[]" value="{{ $course->id }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block" style="font-weight: 600;">{{ $course->title }}</strong>
                                            <small class="text-muted">{{ Str::limit($course->description, 100) }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige forløb</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i> Tilbyd dette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Resources Modal -->
    <div class="modal fade" id="assignResourcesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-photo-film me-2" style="color: var(--primary-color);"></i>
                        Tilbyd materialer til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-resources', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke materialer der skal være tilgængelige for medlemmer af denne liste.</p>

                        @if($availableResources->count() > 0)
                            <div class="list-group">
                                @foreach($availableResources as $resource)
                                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor: pointer;">
                                        <input class="form-check-input mt-1" type="checkbox" name="resource_ids[]" value="{{ $resource->id }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block" style="font-weight: 600;">{{ $resource->title }}</strong>
                                            <small class="text-muted">{{ Str::limit($resource->description, 100) }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige materialer</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i> Tilbyd dette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Bekræft sletning af mailingliste</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($mailingList->activeMembers->count() > 0)
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <strong>Advarsel!</strong> Denne mailingliste har <strong>{{ $mailingList->activeMembers->count() }} {{ $mailingList->activeMembers->count() === 1 ? 'medlem' : 'medlemmer' }}</strong>.
                            Alle medlemmer vil miste ALLE deres adgange til forløb og materialer.
                        </div>
                        <p class="mb-3">For at bekræfte sletningen, skal du skrive <strong>SLETMIG</strong> i feltet nedenfor:</p>
                        <form id="deleteForm" action="{{ route('creator.mailing-lists.destroy', $mailingList) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="mb-3">
                                <input type="text"
                                       class="form-control"
                                       id="deleteConfirmation"
                                       placeholder="Skriv SLETMIG"
                                       autocomplete="off">
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                                    <i class="fa-solid fa-trash me-1"></i> Slet mailingliste
                                </button>
                            </div>
                        </form>
                    @else
                        <p>Er du sikker på, at du vil slette denne mailingliste?</p>
                        <p class="text-muted small mb-0">Denne handling kan ikke fortrydes.</p>
                        <form action="{{ route('creator.mailing-lists.destroy', $mailingList) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i> Slet mailingliste
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Courses Modal -->
    <div class="modal fade" id="assignCoursesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-circle-play me-2" style="color: var(--primary-color);"></i>
                        Tilbyd forløb til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-courses', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke forløb der skal være tilgængelige for medlemmer af denne liste.</p>

                        @if($availableCourses->count() > 0)
                            <div class="list-group">
                                @foreach($availableCourses as $course)
                                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor: pointer;">
                                        <input class="form-check-input mt-1" type="checkbox" name="course_ids[]" value="{{ $course->id }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block" style="font-weight: 600;">{{ $course->title }}</strong>
                                            <small class="text-muted">{{ Str::limit($course->description, 100) }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige forløb</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i> Tilbyd dette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Resources Modal -->
    <div class="modal fade" id="assignResourcesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-photo-film me-2" style="color: var(--primary-color);"></i>
                        Tilbyd materialer til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-resources', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke materialer der skal være tilgængelige for medlemmer af denne liste.</p>

                        @if($availableResources->count() > 0)
                            <div class="list-group">
                                @foreach($availableResources as $resource)
                                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor: pointer;">
                                        <input class="form-check-input mt-1" type="checkbox" name="resource_ids[]" value="{{ $resource->id }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block" style="font-weight: 600;">{{ $resource->title }}</strong>
                                            <small class="text-muted">{{ Str::limit($resource->description, 100) }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige materialer</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i> Tilbyd dette
                        </button>
                    </div>
                </form>
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
        .table {
            font-size: 14px;
        }
        .table th,
        .table td {
            padding: 12px 16px;
        }
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
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

    <script>
        let fileHeaders = [];
        let fileRows = [];

        // Handle file selection
        function handleFileSelect(input) {
            const file = input.files[0];
            if (file) {
                document.getElementById('file-name').textContent = file.name;
                document.getElementById('file-preview').style.display = 'block';
            }
        }

        // Clear file upload
        function clearFileUpload() {
            document.getElementById('importFile').value = '';
            document.getElementById('file-preview').style.display = 'none';
        }

        // Handle file upload
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('importFile');
            const file = fileInput.files[0];

            if (!file) {
                alert('Vælg venligst en fil');
                return;
            }

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Indlæser...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData();
                formData.append('file', file);

                const response = await fetch('{{ route('creator.mailing-lists.parse-import', $mailingList) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    fileHeaders = data.headers;
                    fileRows = data.rows;

                    populateFieldSelects(data.headers);
                    document.getElementById('uploadSection').style.display = 'none';
                    document.getElementById('mappingSection').style.display = 'block';
                    document.getElementById('fileData').value = JSON.stringify(data);
                } else {
                    alert('Fejl ved indlæsning af fil: ' + (data.message || 'Ukendt fejl'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Der opstod en fejl ved upload af filen');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Populate field mapping selects
        function populateFieldSelects(headers) {
            const emailSelect = document.getElementById('emailField');
            const nameSelect = document.getElementById('nameField');

            emailSelect.innerHTML = '<option value="">Vælg...</option>';
            nameSelect.innerHTML = '<option value="">Vælg...</option>';

            headers.forEach((header, index) => {
                const emailOption = new Option(header, index);
                const nameOption = new Option(header, index);

                emailSelect.add(emailOption);
                nameSelect.add(nameOption);

                const lowerHeader = header.toLowerCase();
                if (lowerHeader.includes('email') || lowerHeader.includes('e-mail')) {
                    emailSelect.value = index;
                } else if (lowerHeader.includes('name') || lowerHeader.includes('navn')) {
                    nameSelect.value = index;
                }
            });
        }

        // Handle import submission
        document.getElementById('mappingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const emailField = document.getElementById('emailField').value;
            if (!emailField) {
                alert('Du skal vælge en kolonne til email');
                return;
            }

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Importerer...';
            submitBtn.disabled = true;

            try {
                const formData = {
                    headers: fileHeaders,
                    rows: fileRows,
                    email_field: parseInt(emailField),
                    name_field: document.getElementById('nameField').value ? parseInt(document.getElementById('nameField').value) : null,
                    skip_duplicates: document.getElementById('skipDuplicates').checked
                };

                const response = await fetch('{{ route('creator.mailing-lists.process-import', $mailingList) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    alert(`Import gennemført!\n\n${data.imported} medlemmer importeret\n${data.skipped} duplikater sprunget over`);
                    window.location.reload();
                } else {
                    alert('Fejl ved import: ' + (data.message || 'Ukendt fejl'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Der opstod en fejl ved import');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });

        // Reset upload form
        function resetUpload() {
            document.getElementById('uploadSection').style.display = 'block';
            document.getElementById('mappingSection').style.display = 'none';
            document.getElementById('uploadForm').reset();
            fileHeaders = [];
            fileRows = [];
        }

        function searchMembers() {
            const searchInput = document.getElementById('memberSearch');
            const searchTerm = searchInput.value.toLowerCase();
            const table = document.querySelector('.table tbody');
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Delete confirmation handling
        const deleteConfirmationInput = document.getElementById('deleteConfirmation');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteModal = document.getElementById('deleteModal');

        if (deleteConfirmationInput && confirmDeleteBtn) {
            deleteConfirmationInput.addEventListener('input', function() {
                if (this.value === 'SLETMIG') {
                    confirmDeleteBtn.disabled = false;
                } else {
                    confirmDeleteBtn.disabled = true;
                }
            });

            deleteModal.addEventListener('hidden.bs.modal', function() {
                deleteConfirmationInput.value = '';
                confirmDeleteBtn.disabled = true;
            });
        }
    </script>

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

        /* Dropdown on hover */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin-top: 0;
        }
    </style>
</x-app-layout>
