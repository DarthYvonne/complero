<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $mailingList->name }}</strong>
@endsection

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
            <div class="col-lg-8">
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

                <!-- Courses -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> Forløb @if($mailingList->courses->count() > 0)(<b>{{ $mailingList->courses->count() }}</b>)@endif
                        </h5>
                        @if($availableCourses->count() > 0)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignCoursesModal">
                                <i class="fa-solid fa-plus me-1"></i> Tilbyd forløb
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($mailingList->courses->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->courses as $course)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $course->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($course->description, 80) }}</small>
                                        </div>
                                        <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye"></i> Se
                                        </a>
                                    </div>
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
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-photo-film" style="color: var(--primary-color);"></i> Downloads @if($mailingList->resources->count() > 0)(<b>{{ $mailingList->resources->count() }}</b>)@endif
                        </h5>
                        @if($availableResources->count() > 0)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignResourcesModal">
                                <i class="fa-solid fa-plus me-1"></i> Tilbyd download
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($mailingList->resources->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->resources as $resource)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $resource->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($resource->description, 80) }}</small>
                                        </div>
                                        <a href="{{ route('creator.resources.show', $resource) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye"></i> Se
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen downloads tildelt denne liste endnu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- List Details -->
                <div class="card mb-3">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-info-circle" style="color: var(--primary-color);"></i> Liste detaljer
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="mb-3">
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

                        <hr class="my-3">

                        <!-- Created/Updated -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Oprettet</strong>
                            <span style="font-weight: 300; color: #666;">{{ $mailingList->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <div>
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Sidst opdateret</strong>
                            <span style="font-weight: 300; color: #666;">{{ $mailingList->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-bolt" style="color: var(--primary-color);"></i> Handlinger
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('creator.mailing-lists.destroy', $mailingList) }}" method="POST"
                              onsubmit="return confirm('Er du sikker på, at du vil slette denne liste? Kurser og ressourcer vil blive gjort tilgængelige for alle.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fa-solid fa-trash"></i> Slet liste
                            </button>
                        </form>
                    </div>
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
                        Tilbyd downloads til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-resources', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke downloads der skal være tilgængelige for medlemmer af denne liste.</p>

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
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige downloads</p>
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
    </script>
</x-app-layout>
