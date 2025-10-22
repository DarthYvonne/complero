<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Downloads</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Downloads
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer alt download på platformen
                    </p>
                </div>
                <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-plus me-1"></i> Opret download
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
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 clickable-card" data-href="{{ route('admin.resources.show', $resource) }}" style="cursor: pointer;">
                        @if($resource->image_url)
                            <img src="{{ Storage::url($resource->image_url) }}" class="card-img-top" alt="{{ $resource->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa-solid fa-file-lines text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 10px;">{{ $resource->title }}</h5>
                                @if($resource->is_published)
                                    <span class="badge bg-success">Publiceret</span>
                                @else
                                    <span class="badge bg-secondary">Kladde</span>
                                @endif
                            </div>
                            <p style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">{{ Str::limit($resource->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size: 13px; font-weight: 300; color: #999;">
                                    <i class="fa-solid fa-user"></i> {{ $resource->creator->name }}
                                </span>
                                <span style="font-size: 16px; font-weight: 600; color: #333;">
                                    @if($resource->is_free)
                                        Gratis
                                    @else
                                        €{{ number_format($resource->price, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('admin.resources.show', $resource) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i> Se
                                </a>
                                <a href="{{ route('admin.resources.edit', $resource) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i> Rediger
                                </a>
                                <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Er du sikker på, at du vil slette dette download?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i> Slet
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <i class="fa-solid fa-glasses" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Intet download endnu</h5>
                            <p class="text-muted mb-3" style="font-weight: 300;">Kom i gang med at oprette dit første download</p>
                            <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-circle-plus me-1"></i> Opret download
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
            background: #be185d;
            border-color: #be185d;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
        .btn-primary:hover {
            background: #9f1239;
            border-color: #9f1239;
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
