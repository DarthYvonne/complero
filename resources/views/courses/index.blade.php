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
                        Udforsk tilgængelige forløb
                    </p>
                </div>
                @if(Auth::user() && in_array(Auth::user()->role, ['admin', 'creator']))
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-circle-plus me-1"></i> Tilføj forløb
                    </a>
                @endif
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="row g-3">
            @forelse($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 clickable-card" data-href="{{ route('courses.show', $course) }}" style="cursor: pointer;">
                        @if($course->image_url)
                            <img src="{{ Storage::url($course->image_url) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fa-solid fa-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 10px;">
                                {{ $course->title }}
                            </h5>
                            <p class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-size: 13px; font-weight: 300; color: #999;">
                                    <i class="fa-solid fa-user"></i> {{ $course->creator->name }}
                                </span>
                                <span style="font-size: 16px; font-weight: 600; color: #333;">
                                    @if($course->is_free)
                                        Gratis
                                    @else
                                        €{{ number_format($course->price, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-primary w-100">
                                <i class="fa-solid fa-arrow-right me-1"></i> Se forløb
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <i class="fa-solid fa-photo-film" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Ingen forløb tilgængelige endnu</h5>
                            <p class="text-muted mb-0" style="font-weight: 300;">Forløb vil blive tilgængelige snart</p>
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
