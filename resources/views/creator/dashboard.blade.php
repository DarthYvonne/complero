<x-app-layout>
    @section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Overblik</strong>
    @endsection
    <div class="container-fluid">
        @php
            $user = Auth::user();
            $organizationName = 'Complicero';

            if ($user && in_array($user->role, ['admin', 'creator'])) {
                $organizationName = $user->organization_name ?: $user->name;
            }
        @endphp

        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                <i class="fa-solid fa-gauge" style="color: #be185d;"></i> {{ $organizationName }}
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Administrer din platform
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Mine Forløb</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_courses'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Publiceret</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['published_courses'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Mine Downloads</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_resources'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Mine Lister</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_mailing_lists'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 15px;">Hurtige handlinger</h2>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('creator.courses.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Opret forløb
                        </a>
                        <a href="{{ route('creator.courses.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-book me-1"></i> Se mine forløb
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 15px;">Seneste forløb</h2>
            <div class="card">
                <div class="card-body">
                    @forelse($recentCourses as $course)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <strong>{{ $course->title }}</strong>
                                <div class="text-muted small">{{ $course->lessons->count() }} lektioner</div>
                            </div>
                            <div>
                                @if($course->is_published)
                                    <span class="badge bg-success">Publiceret</span>
                                @else
                                    <span class="badge bg-secondary">Kladde</span>
                                @endif
                                <a href="{{ route('creator.courses.show', $course) }}" class="btn btn-sm btn-outline-primary ms-2">
                                    Se detaljer
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            Ingen forløb endnu. <a href="{{ route('creator.courses.create') }}">Opret dit første forløb</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn {
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-primary {
            background: #be185d;
            border-color: #be185d;
        }

        .btn-primary:hover {
            background: #9f1239;
            border-color: #9f1239;
        }

        .btn-outline-primary {
            color: #be185d;
            border-color: #be185d;
        }

        .btn-outline-primary:hover {
            background: #be185d;
            border-color: #be185d;
            color: white;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }
    </style>
</x-app-layout>