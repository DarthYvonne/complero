<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Forløb</strong>
@endsection
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Forløb
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer alle forløb på platformen
                    </p>
                </div>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-plus me-1"></i> Opret forløb
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Courses Grid -->
        <div class="row g-3">
            @forelse($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 clickable-card" data-href="{{ route('admin.courses.show', $course) }}" style="cursor: pointer;">
                        @if($course->image_url)
                            <img src="{{ Storage::url($course->image_url) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa-solid fa-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $course->title }}</h5>
                                @if($course->is_published)
                                    <span class="badge bg-success">Publiceret</span>
                                @else
                                    <span class="badge bg-secondary">Kladde</span>
                                @endif
                            </div>
                            <p class="card-text text-muted small">{{ Str::limit($course->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    <i class="fa-solid fa-user"></i> {{ $course->creator->name }}
                                </span>
                                <span class="fw-bold">
                                    @if($course->is_free)
                                        Gratis
                                    @else
                                        €{{ number_format($course->price, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-eye"></i> Se
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i> Rediger
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Er du sikker på, at du vil slette dette forløb?');">
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
                            <i class="fa-solid fa-photo-film" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Ingen forløb endnu</h5>
                            <p class="text-muted mb-3">Kom i gang med at oprette dit første forløb</p>
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-circle-plus me-1"></i> Opret forløb
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($courses->hasPages())
            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        @endif
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
