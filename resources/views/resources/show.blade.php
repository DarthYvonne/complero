<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('resources.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Downloads</a>
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
                        <i class="fa-solid fa-user"></i> {{ $resource->creator->name }}
                        <span style="margin: 0 8px;">•</span>
                        <i class="fa-solid fa-calendar"></i> {{ $resource->created_at->format('d/m/Y') }}
                        @if($resource->files->count() > 0)
                            <span style="margin: 0 8px;">•</span>
                            <i class="fa-solid fa-file"></i> {{ $resource->files->count() }} {{ $resource->files->count() === 1 ? 'fil' : 'filer' }}
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('resources.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til downloadr
                    </a>
                </div>
            </div>
        </div>

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
                                <i class="fa-solid fa-align-left" style="color: #15803d;"></i> Om dette download
                            </h5>
                            <div style="font-size: 14px; font-weight: 300; color: #666;">{!! $resource->description !!}</div>
                        </div>
                    @endif
                </div>

                <!-- Downloadable Files -->
                @if($resource->files->count() > 0)
                    <div class="card">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-download" style="color: #15803d;"></i> Filer til download
                                <span class="badge bg-secondary ms-2">{{ $resource->files->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($resource->files as $file)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-file me-2" style="color: #15803d; font-size: 1.2rem;"></i>
                                                    <div>
                                                        <strong style="font-size: 16px; font-weight: 600; color: #333;">{{ $file->filename }}</strong>
                                                        <div style="font-size: 13px; font-weight: 300; color: #999;">{{ number_format($file->file_size / 1024, 2) }} KB</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-primary">
                                                    <i class="fa-solid fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fa-solid fa-file-lines" style="font-size: 3rem; color: #d1d5db;"></i>
                            <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen filer tilgængelige endnu</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Resource Info -->
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info" style="color: #15803d;"></i> Downloads information
                        </h5>

                        <!-- Price -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Pris</strong>
                            @if($resource->is_free)
                                <span style="color: #10b981; font-weight: 600; font-size: 20px;">
                                    <i class="fa-solid fa-gift"></i> Gratis
                                </span>
                            @else
                                <span style="font-weight: 600; font-size: 20px;">€{{ number_format($resource->price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Files Count -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Antal filer</strong>
                            <span style="font-size: 14px; font-weight: 300; color: #666;">{{ $resource->files->count() }}</span>
                        </div>

                        <!-- Total File Size -->
                        @if($resource->files->sum('file_size') > 0)
                            <div>
                                <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Samlet størrelse</strong>
                                <span style="font-size: 14px; font-weight: 300; color: #666;">{{ number_format($resource->files->sum('file_size') / 1024 / 1024, 2) }} MB</span>
                            </div>
                        @endif
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
            border-radius: 6px;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .btn-outline-secondary {
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
            border-color: #f0f0f0;
            padding: 1rem 0;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
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
