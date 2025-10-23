<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.resources.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Materialer</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $resource->title }}</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Materiale: <span style="font-weight: 100;">{{ $resource->title }}</span>
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Af: {{ $resource->creator->name }}
                        <span class="mx-2">•</span>
                        {{ $resource->files->count() }} {{ $resource->files->count() === 1 ? 'fil' : 'filer' }}
                        <span class="mx-2">•</span>
                        Pris: @if($resource->is_free)
                            @if($resource->mailing_list_id)
                                Gratis for medlemmer af {{ $resource->mailingList->name }}
                            @else
                                Gratis for alle
                            @endif
                        @else
                            {{ number_format($resource->price, 2) }} DKK
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('resources.show', $resource) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fa-solid fa-eye me-1"></i> Forhåndsvisning
                    </a>
                    <a href="{{ route('creator.resources.edit', $resource) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen me-1"></i> Rediger
                    </a>
                    <a href="{{ route('creator.resources.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til materialer
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Resource Image -->
                @if($resource->image_url)
                    <div class="card mb-4">
                        <img src="{{ Storage::url($resource->image_url) }}" class="card-img-top" alt="{{ $resource->title }}" style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Description / Tabs -->
                <div class="card mb-4">
                    @if($resource->tabs->count() > 0)
                        <!-- Tabs Navigation -->
                        <div class="card-header bg-white border-bottom" style="padding: 0;">
                            <ul class="nav nav-tabs card-header-tabs" id="resourceTabs" role="tablist" style="border-bottom: none; margin-bottom: 0;">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="intro-tab" data-bs-toggle="tab" data-bs-target="#intro" type="button" role="tab" aria-controls="intro" aria-selected="true">
                                        Intro
                                    </button>
                                </li>
                                @foreach($resource->tabs as $index => $tab)
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
                            <div class="tab-content" id="resourceTabsContent">
                                <div class="tab-pane fade show active" id="intro" role="tabpanel" aria-labelledby="intro-tab">
                                    {!! $resource->description !!}
                                </div>
                                @foreach($resource->tabs as $tab)
                                    <div class="tab-pane fade" id="tab-{{ $tab->id }}" role="tabpanel" aria-labelledby="tab-{{ $tab->id }}-tab">
                                        {!! $tab->content !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- No tabs, just show description -->
                        <div class="card-body">
                            <div style="font-size: 16px; font-weight: 300; color: #666;">{!! $resource->description !!}</div>
                        </div>
                    @endif
                </div>

                <!-- Downloadable Files -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            Filer til download ({{ $resource->files->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($resource->files->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($resource->files as $file)
                                    @php
                                        $extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                                        $iconClass = match($extension) {
                                            'pdf' => 'fa-file-pdf',
                                            'doc', 'docx' => 'fa-file-word',
                                            'xls', 'xlsx' => 'fa-file-excel',
                                            'ppt', 'pptx' => 'fa-file-powerpoint',
                                            'zip', 'rar', '7z' => 'fa-file-zipper',
                                            'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => 'fa-file-image',
                                            'mp4', 'avi', 'mov', 'wmv' => 'fa-file-video',
                                            'mp3', 'wav', 'ogg' => 'fa-file-audio',
                                            'txt' => 'fa-file-lines',
                                            default => 'fa-file',
                                        };
                                        $iconColor = match($extension) {
                                            'pdf' => '#dc2626',
                                            'doc', 'docx' => '#2563eb',
                                            'xls', 'xlsx' => '#16a34a',
                                            'ppt', 'pptx' => '#ea580c',
                                            default => 'var(--primary-color)',
                                        };
                                    @endphp
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-decoration-none flex-grow-1" style="color: inherit;">
                                            <i class="fa-solid {{ $iconClass }} me-2" style="color: {{ $iconColor }}; font-size: 1.5rem;"></i>
                                            <strong style="font-size: 14px; font-weight: 500;">{{ $file->filename }}</strong>
                                            <small class="text-muted ms-2">({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                        </a>
                                        <form action="{{ route('creator.resources.files.destroy', [$resource, $file]) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Er du sikker på, at du vil slette denne fil?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 14px; text-decoration: none;">
                                                <i class="fa-solid fa-trash me-1"></i> Slet
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa-solid fa-file-lines" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-2" style="font-weight: 300; color: #666;">Ingen filer endnu</p>
                                <p class="small text-muted" style="font-weight: 300;">Tilføj filer, som brugerne kan downloade</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publiceret Status Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">Publiceret</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div style="font-weight: 500; font-size: 15px; color: #333;">
                                    <span id="publish-status-text">{{ $resource->is_published ? 'Materialet er publiceret' : 'Materialet er ikke publiceret' }}</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    {{ $resource->is_published ? 'Synligt for brugere' : 'Kun synligt for dig' }}
                                </div>
                            </div>
                            <div class="form-check form-switch" style="font-size: 1.5rem;">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_published"
                                       style="cursor: pointer;"
                                       {{ $resource->is_published ? 'checked' : '' }}
                                       onchange="togglePublishStatus(this)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">Pris</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            @if($resource->mailingLists->count() > 0)
                                <span style="font-weight: 500; color: #333;">
                                    For medlemmer af {{ $resource->mailingLists->pluck('name')->join(', ', ' og ') }}
                                </span>
                            @elseif($resource->is_free)
                                <span style="font-weight: 500; color: #333;">Gratis</span>
                            @else
                                <span style="font-weight: 500; color: #333;">{{ number_format($resource->price, 2) }} Kr.</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Files Count Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">Antal filer</h5>
                    </div>
                    <div class="card-body">
                        <div style="font-size: 32px; font-weight: 700; color: #333;">
                            {{ $resource->files->count() }}
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">Handlinger</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fa-solid fa-trash"></i> Slet download
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Bekræft sletning af download</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($resource->files->count() > 0)
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <strong>Advarsel!</strong> Dette download har <strong>{{ $resource->files->count() }} {{ $resource->files->count() === 1 ? 'fil' : 'filer' }}</strong>.
                            Alle filer vil også blive slettet permanent.
                        </div>
                        <p class="mb-3">For at bekræfte sletningen, skal du skrive <strong>SLETMIG</strong> i feltet nedenfor:</p>
                        <form id="deleteForm" action="{{ route('creator.resources.destroy', $resource) }}" method="POST">
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
                                    <i class="fa-solid fa-trash me-1"></i> Slet download
                                </button>
                            </div>
                        </form>
                    @else
                        <p>Er du sikker på, at du vil slette dette download?</p>
                        <p class="text-muted small mb-0">Denne handling kan ikke fortrydes.</p>
                        <form action="{{ route('creator.resources.destroy', $resource) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash me-1"></i> Slet download
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
            formData.append('title', '{{ $resource->title }}');
            formData.append('description', '{{ addslashes($resource->description) }}');
            formData.append('is_published', checkbox.checked ? '1' : '0');

            // Submit via fetch
            fetch('{{ route('creator.resources.update', $resource) }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Update UI
                    if (checkbox.checked) {
                        statusText.textContent = 'Materialet er publiceret';
                        statusSubtext.textContent = 'Synligt for brugere';
                    } else {
                        statusText.textContent = 'Materialet er ikke publiceret';
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

        @if($resource->files->count() > 0)
        // Delete confirmation logic for resources with files
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
