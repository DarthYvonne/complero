<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $course->title }}</strong>
@endsection

@php
    $effectiveRole = session('view_as', Auth::user()->role);
@endphp

    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                            Forløb: <span style="font-weight: 100;">{{ $course->title }}</span>
                        </h1>
                        <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                            <i class="fa-solid fa-user"></i> Oprettet af {{ $course->creator->name }}
                            <span class="mx-2">•</span>
                            <i class="fa-solid fa-calendar"></i> {{ $course->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('creator.courses.edit', $course) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen me-1"></i> Rediger
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Image -->
                @if($course->image_url)
                    <div class="card border-0 shadow-sm mb-4">
                        <img src="{{ Storage::url($course->image_url) }}" class="card-img-top" alt="{{ $course->title }}" style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Description / Tabs -->
                <div class="card border-0 shadow-sm mb-4">
                    @if($course->tabs->count() > 0)
                        <!-- Tabs Navigation -->
                        <div class="card-header bg-white border-bottom" style="padding: 0;">
                            <ul class="nav nav-tabs card-header-tabs" id="courseTabs" role="tablist" style="border-bottom: none; margin-bottom: 0;">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="intro-tab" data-bs-toggle="tab" data-bs-target="#intro" type="button" role="tab" aria-controls="intro" aria-selected="true">
                                        {{ $course->intro_title ?? 'Introduktion' }}
                                    </button>
                                </li>
                                @foreach($course->tabs as $index => $tab)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-{{ $tab->id }}-tab" data-bs-toggle="tab" data-bs-target="#tab-{{ $tab->id }}" type="button" role="tab" aria-controls="tab-{{ $tab->id }}" aria-selected="false">
                                            {{ $tab->title }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Tabs Content -->
                        <div class="card-body">
                            <div class="tab-content" id="courseTabsContent">
                                <div class="tab-pane fade show active" id="intro" role="tabpanel" aria-labelledby="intro-tab">
                                    {!! $course->description !!}
                                </div>
                                @foreach($course->tabs as $tab)
                                    <div class="tab-pane fade" id="tab-{{ $tab->id }}" role="tabpanel" aria-labelledby="tab-{{ $tab->id }}-tab">
                                        {!! $tab->content !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- No tabs, just show description -->
                        <div class="card-body">
                            <h5 class="card-title">Beskrivelse</h5>
                            <div class="card-text">{!! $course->description !!}</div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Sidebar -->
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
                                       style="cursor: pointer;"
                                       {{ $course->is_published ? 'checked' : '' }}
                                       onchange="togglePublishStatus(this)">
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
                        @if($course->is_free && $course->mailing_list_id)
                            <div style="font-weight: 500; color: #333;">
                                Gratis for<br>
                                Medlemmer af {{ $course->mailingList->name }}
                            </div>
                        @elseif($course->is_free)
                            <div style="font-weight: 500; color: #333;">
                                Gratis for<br>
                                Alle brugere
                            </div>
                        @else
                            <div class="mb-3">
                                <strong class="d-block text-muted small mb-1">Pris</strong>
                                <span style="font-weight: 500; color: #333;">{{ number_format($course->price, 2) }} DKK</span>
                            </div>
                            <div class="mb-0">
                                <strong class="d-block text-muted small mb-1">Tilgængelig for</strong>
                                @if($course->mailing_list_id)
                                    <span style="font-weight: 500; color: #333;">Medlemmer af {{ $course->mailingList->name }}</span>
                                @else
                                    <span style="font-weight: 500; color: #333;">Alle brugere</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lessons List -->
                <div class="card">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0" style="font-size: 16px; font-weight: 600; color: #333;">
                            Lektioner
                        </h6>
                        @if($effectiveRole === 'admin' || $effectiveRole === 'creator')
                            <a href="{{ route('creator.courses.lessons.create', $course) }}" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-circle-plus me-1"></i> Tilføj lektion
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if($course->lessons->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($course->lessons->sortBy('order') as $lesson)
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}"
                                       class="list-group-item list-group-item-action"
                                       style="border-left: none; border-right: none; padding: 12px 16px;">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="d-flex align-items-start flex-grow-1">
                                                <div class="me-2" style="min-width: 24px;">
                                                    <i class="fa-solid fa-tv" style="color: #999;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div style="font-size: 14px; font-weight: 400; color: #333;">
                                                        {{ $lesson->title }}
                                                    </div>
                                                    @if($lesson->duration_minutes)
                                                        <small style="font-size: 12px; font-weight: 300; color: #999;">
                                                            {{ $lesson->duration_minutes }} min
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($effectiveRole === 'admin' || $effectiveRole === 'creator')
                                                <a href="{{ route('creator.courses.lessons.edit', [$course, $lesson]) }}"
                                                   class="btn btn-sm btn-outline-primary ms-2"
                                                   onclick="event.stopPropagation();">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen lektioner endnu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delete Button -->
                <button type="button" class="btn btn-outline-danger w-100 mt-4" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fa-solid fa-trash"></i> Slet forløb
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Bekræft sletning af forløb</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($course->lessons->count() > 0 || ($course->mailing_list_id && $course->mailingList->activeMembers->count() > 0))
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <strong>Advarsel!</strong>
                            @if($course->lessons->count() > 0)
                                Dette forløb har <strong>{{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'lektion' : 'lektioner' }}</strong>.
                                Alle lektioner vil også blive slettet permanent.
                            @endif
                            @if($course->mailing_list_id && $course->mailingList->activeMembers->count() > 0)
                                <br>
                                <strong>{{ $course->mailingList->activeMembers->count() }} {{ $course->mailingList->activeMembers->count() === 1 ? 'bruger' : 'brugere' }}</strong> vil miste adgang til dette forløb.
                            @endif
                        </div>
                        <p class="mb-3">For at bekræfte sletningen, skal du skrive <strong>SLETMIG</strong> i feltet nedenfor:</p>
                        <form id="deleteForm" action="{{ route('creator.courses.destroy', $course) }}" method="POST">
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
                                    <i class="fa-solid fa-trash me-1"></i> Slet forløb
                                </button>
                            </div>
                        </form>
                    @else
                        <p>Er du sikker på, at du vil slette dette forløb?</p>
                        <p class="text-muted small mb-0">Denne handling kan ikke fortrydes.</p>
                        <form action="{{ route('creator.courses.destroy', $course) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i> Slet forløb
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-header-tabs {
            margin-left: 0;
            margin-right: 0;
        }
        .card-header-tabs .nav-item:first-child .nav-link {
            padding-left: 1rem;
        }
    </style>

    @push('scripts')
    <script>
        function togglePublishStatus(checkbox) {
            const statusText = document.getElementById('publish-status-text');
            const statusSubtext = statusText.parentElement.nextElementSibling;

            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PATCH');
            formData.append('title', '{{ $course->title }}');
            formData.append('description', '{{ addslashes($course->description) }}');
            formData.append('is_published', checkbox.checked ? '1' : '0');

            // Submit via fetch
            fetch('{{ route('creator.courses.update', $course) }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Update UI
                    if (checkbox.checked) {
                        statusText.textContent = 'Forløbet er publiceret';
                        statusSubtext.textContent = 'Synligt for brugere';
                    } else {
                        statusText.textContent = 'Forløbet er ikke publiceret';
                        statusSubtext.textContent = 'Kun synligt for dig';
                    }
                } else {
                    alert('Der opstod en fejl. Prøv igen.');
                    // Revert checkbox
                    checkbox.checked = !checkbox.checked;
                }
            })
            .catch(error => {
                alert('Der opstod en fejl. Prøv igen.');
                // Revert checkbox
                checkbox.checked = !checkbox.checked;
            });
        }

        @if($course->lessons->count() > 0)
        // Delete confirmation logic for courses with lessons
        document.addEventListener('DOMContentLoaded', function() {
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

                // Reset input when modal is closed
                deleteModal.addEventListener('hidden.bs.modal', function() {
                    deleteConfirmationInput.value = '';
                    confirmDeleteBtn.disabled = true;
                });
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>
