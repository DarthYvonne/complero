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
                <a href="{{ route('creator.courses.create') }}" class="btn btn-primary">
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
                @php
                    $courseColor = $course->primary_color ?? '#be185d';
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card clickable-card" data-href="{{ route('creator.courses.show', $course) }}" style="cursor: pointer;">
                        <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                    <i class="fa-solid fa-circle-play" style="color: {{ $courseColor }};"></i> {{ $course->title }}
                                </h5>
                                @if($course->is_published)
                                    <span class="badge bg-success">Publiceret</span>
                                @else
                                    <span class="badge bg-secondary">Kladde</span>
                                @endif
                            </div>
                            <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                                {!! Str::limit(strip_tags($course->description), 200) !!}
                                @if(strlen(strip_tags($course->description)) > 200)
                                    <a href="{{ route('creator.courses.show', $course) }}" style="color: {{ $courseColor }}; text-decoration: underline;">læs resten</a>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size: 13px; font-weight: 300; color: #999;">
                                    <span style="color: {{ $courseColor }}; font-weight: 600;">Af:</span> {{ $course->creator->organization_name ?: $course->creator->name }}
                                </span>
                                <span style="font-size: 16px; font-weight: 600; color: #333;">
                                    @if($course->is_free)
                                        Gratis for medlemmer
                                    @else
                                        €{{ number_format($course->price, 2) }}
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
                            <i class="fa-solid fa-photo-film" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Ingen forløb endnu</h5>
                            <p class="text-muted mb-3">Kom i gang med at oprette dit første forløb</p>
                            <a href="{{ route('creator.courses.create') }}" class="btn btn-primary">
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
