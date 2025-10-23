<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('resources.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Materialer</a>
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
                        Pris: @if($resource->is_free)Gratis for dig@else{{ number_format($resource->price, 2) }} DKK@endif
                    </p>
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
                <div class="card">
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
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Downloadable Files -->
                <div class="card">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-download" style="color: var(--primary-color);"></i> {{ $resource->files->count() === 1 ? 'Materiale' : 'Materialer' }}
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
                                            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'fa-file-image',
                                            'mp4', 'avi', 'mov', 'wmv' => 'fa-file-video',
                                            'mp3', 'wav', 'ogg' => 'fa-file-audio',
                                            'txt' => 'fa-file-lines',
                                            default => 'fa-file'
                                        };
                                    @endphp
                                    <div class="list-group-item d-flex justify-content-between align-items-center" style="padding: 12px 0;">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid {{ $iconClass }} me-3" style="color: var(--primary-color); font-size: 1.2rem;"></i>
                                                <div>
                                                    <div style="font-size: 14px; font-weight: 500; color: #333;">{{ $file->filename }}</div>
                                                    <div class="text-muted small">{{ number_format($file->file_size / 1024, 2) }} KB</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($file->file_path) }}" download style="text-decoration: none; white-space: nowrap;">
                                            <i class="fa-solid fa-cloud-arrow-down" style="color: var(--primary-color); font-size: 1.1rem; margin-right: 6px;"></i>
                                            <span style="color: var(--primary-color); font-weight: 500; font-size: 14px;">Hent</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-file-lines" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen filer tilgængelige endnu</p>
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
