<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Indhold</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Indhold
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer dine forløb og materialer
                    </p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @if($tab === 'courses')
                        <a href="{{ route('creator.courses.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Opret forløb
                        </a>
                    @else
                        <a href="{{ route('creator.resources.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Opret materiale
                        </a>
                    @endif
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

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab === 'courses' ? 'active' : '' }}" href="{{ route('creator.content.index', ['tab' => 'courses']) }}">
                    <i class="fa-solid fa-circle-play me-1"></i> Forløb ({{ $courses->count() }})
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab === 'resources' ? 'active' : '' }}" href="{{ route('creator.content.index', ['tab' => 'resources']) }}">
                    <i class="fa-solid fa-photo-film me-1"></i> Materialer ({{ $resources->count() }})
                </a>
            </li>
        </ul>

        <!-- Content Area -->
        @if($tab === 'courses')
            <!-- Courses Tab -->
            <div class="row g-3">
                @forelse($courses as $course)
                    <x-course-card :course="$course" />
                @empty
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fa-solid fa-circle-play" style="font-size: 4rem; color: #d1d5db;"></i>
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
        @else
            <!-- Resources Tab -->
            <div class="row g-3">
                @forelse($resources as $resource)
                    <x-resource-card :resource="$resource" />
                @empty
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fa-solid fa-photo-film" style="font-size: 4rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 mb-2">Ingen materialer endnu</h5>
                                <p class="text-muted mb-3">Kom i gang med at oprette dit første materiale</p>
                                <a href="{{ route('creator.resources.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-plus me-1"></i> Opret materiale
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: none;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

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
