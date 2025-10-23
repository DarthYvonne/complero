<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Materialer</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Materialer
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer alle materialer på platformen
                    </p>
                </div>
                <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-plus me-1"></i> Opret materiale
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Resources Grid -->
        <div class="row g-3">
            @forelse($resources as $resource)
                @php
                    $resourceColor = '#be185d'; // Resources always use magenta
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card clickable-card" data-href="{{ route('admin.resources.show', $resource) }}" style="cursor: pointer;">
                        <img src="{{ $resource->image }}" class="card-img-top" alt="{{ $resource->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                    <i class="fa-solid fa-photo-film" style="color: {{ $resourceColor }};"></i> {{ $resource->title }}
                                </h5>
                                @if($resource->is_published)
                                    <span class="badge bg-success">Publiceret</span>
                                @else
                                    <span class="badge bg-secondary">Kladde</span>
                                @endif
                            </div>
                            <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                                {!! Str::limit(strip_tags($resource->description), 200) !!}
                                @if(strlen(strip_tags($resource->description)) > 200)
                                    <a href="{{ route('admin.resources.show', $resource) }}" style="color: {{ $resourceColor }}; text-decoration: underline;">læs resten</a>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size: 13px; font-weight: 300; color: #999;">
                                    <span style="color: {{ $resourceColor }}; font-weight: 600;">Af:</span> {{ $resource->creator->organization_name ?: $resource->creator->name }}
                                </span>
                                <span style="font-size: 16px; font-weight: 600; color: #333;">
                                    @if($resource->is_free)
                                        Gratis for medlemmer
                                    @else
                                        €{{ number_format($resource->price, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <i class="fa-solid fa-glasses" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Intet materiale endnu</h5>
                            <p class="text-muted mb-3" style="font-weight: 300;">Kom i gang med at oprette dit første materiale</p>
                            <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-circle-plus me-1"></i> Opret materiale
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make cards clickable except when clicking buttons
            document.querySelectorAll('.clickable-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't navigate if clicking a button or form element
                    if (!e.target.closest('button, a, form')) {
                        window.location.href = this.dataset.href;
                    }
                });
            });
        });
    </script>
</x-app-layout>
