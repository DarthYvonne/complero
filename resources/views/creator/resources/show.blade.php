<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.resources.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Downloads</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $resource->title }}</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Download: <span style="font-weight: 100;">{{ $resource->title }}</span>
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        <i class="fa-solid fa-user"></i> Oprettet af {{ $resource->creator->name }}
                        <span style="margin: 0 8px;">•</span>
                        <i class="fa-solid fa-calendar"></i> {{ $resource->created_at->format('d/m/Y') }}
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
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til download
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
                            <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                                <i class="fa-solid fa-align-left" style="color: var(--primary-color);"></i> Beskrivelse
                            </h5>
                            <div style="font-size: 14px; font-weight: 300; color: #666;">{!! $resource->description !!}</div>
                        </div>
                    @endif
                </div>

                <!-- Downloadable Files -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-download" style="color: var(--primary-color);"></i> Filer til download
                            <span class="badge bg-secondary ms-2">{{ $resource->files->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($resource->files->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($resource->files as $file)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fa-solid fa-file me-2" style="color: var(--primary-color);"></i>
                                            <strong style="font-size: 14px; font-weight: 500;">{{ $file->filename }}</strong>
                                            <small class="text-muted ms-2">({{ number_format($file->file_size / 1024, 2) }} KB)</small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-outline-primary">
                                                <i class="fa-solid fa-eye"></i> Se
                                            </a>
                                            <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-outline-primary">
                                                <i class="fa-solid fa-download"></i> Download
                                            </a>
                                            <form action="{{ route('creator.resources.files.destroy', [$resource, $file]) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Er du sikker på, at du vil slette denne fil?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
                <!-- Resource Details -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">Downloads detaljer</h5>

                        <!-- Status -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Status</strong>
                            @if($resource->is_published)
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-circle-check"></i> Publiceret
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fa-solid fa-file"></i> Kladde
                                </span>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Pris</strong>
                            @if($resource->is_free)
                                <span style="color: #10b981; font-weight: 600;">
                                    <i class="fa-solid fa-gift"></i> Gratis
                                </span>
                            @else
                                <span style="font-weight: 600;">€{{ number_format($resource->price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">URL-slug</strong>
                            <code class="small">{{ $resource->slug }}</code>
                        </div>

                        <!-- Files Count -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Antal filer</strong>
                            {{ $resource->files->count() }}
                        </div>

                        <!-- Downloads -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Downloads</strong>
                            0
                        </div>

                        <!-- Created/Updated -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Oprettet</strong>
                            {{ $resource->created_at->format('d/m/Y H:i') }}
                        </div>

                        <div>
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Sidst opdateret</strong>
                            {{ $resource->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">Handlinger</h5>

                        <div class="d-grid gap-2">
                            <a href="{{ route('creator.resources.edit', $resource) }}" class="btn btn-primary">
                                <i class="fa-solid fa-pen"></i> Rediger download
                            </a>

                            <hr class="my-2">

                            <form action="{{ route('creator.resources.destroy', $resource) }}" method="POST"
                                  onsubmit="return confirm('Er du sikker på, at du vil slette dette download? Alle filer vil også blive slettet.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fa-solid fa-trash"></i> Slet download
                                </button>
                            </form>
                        </div>
                    </div>
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
        .card-header-tabs {
            margin-left: 0;
            margin-right: 0;
        }
        .card-header-tabs .nav-item:first-child .nav-link {
            padding-left: 1rem;
        }
    </style>
</x-app-layout>
