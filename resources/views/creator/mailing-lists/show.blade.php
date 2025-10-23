<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $mailingList->name }}</strong>
@endsection

@php
    $effectiveRole = session('view_as', Auth::user()->role);
@endphp

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                        Mailingliste: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('creator.mailing-lists.edit', $mailingList) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen me-1"></i> Rediger
                    </a>
                    <a href="{{ route('creator.mailing-lists.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                    </a>
                </div>
            </div>
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

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-list me-1"></i> Mailingliste
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
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

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-7">
                <!-- Members -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-circle-user" style="color: var(--primary-color);"></i> Medlemmer @if($mailingList->activeMembers->count() > 0)(<b>{{ $mailingList->activeMembers->count() }}</b>)@endif
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

                <!-- Courses -->
                <div class="card mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> Forløb til medlemmer @if($mailingList->courses->count() > 0)(<b>{{ $mailingList->courses->count() }}</b>)@endif
                        </h5>
                        @if(($effectiveRole === 'admin' || $effectiveRole === 'creator') && $availableCourses->count() > 0)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignCoursesModal">
                                <i class="fa-solid fa-plus me-1"></i> Tilbyd forløb
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($mailingList->courses->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->courses as $course)
                                    <a href="{{ route('creator.courses.show', $course) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="text-decoration: none; color: inherit;">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $course->title }}</strong>
                                            <br>
                                            <small class="text-muted">{!! Str::limit(strip_tags($course->description), 80) !!}</small>
                                        </div>
                                        <i class="fa-solid fa-chevron-right" style="color: #999;"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen forløb tildelt denne liste endnu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Resources -->
                <div class="card mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-photo-film" style="color: var(--primary-color);"></i> Materialer til medlemmer @if($mailingList->resources->count() > 0)(<b>{{ $mailingList->resources->count() }}</b>)@endif
                        </h5>
                        @if(($effectiveRole === 'admin' || $effectiveRole === 'creator') && $availableResources->count() > 0)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignResourcesModal">
                                <i class="fa-solid fa-plus me-1"></i> Tilbyd materiale
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($mailingList->resources->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->resources as $resource)
                                    <a href="{{ route('creator.resources.show', $resource) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="text-decoration: none; color: inherit;">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $resource->title }}</strong>
                                            <br>
                                            <small class="text-muted">{!! Str::limit(strip_tags($resource->description), 80) !!}</small>
                                        </div>
                                        <i class="fa-solid fa-chevron-right" style="color: #999;"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen materialer tildelt denne liste endnu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delete Button -->
                <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fa-solid fa-trash"></i> Slet liste
                </button>
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
    </style>

    <script>
        function toggleStatus(checkbox) {
            const isActive = checkbox.checked;
            const label = document.getElementById('statusLabel');

            // Update label
            label.textContent = isActive ? 'Aktiv' : 'Inaktiv';

            // Send update to server
            fetch('{{ route('creator.mailing-lists.update', $mailingList) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: JSON.stringify({
                    name: '{{ $mailingList->name }}',
                    description: '{{ $mailingList->description }}',
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
                // Revert toggle on error
                checkbox.checked = !isActive;
                label.textContent = !isActive ? 'Aktiv' : 'Inaktiv';
            });
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
</x-app-layout>
