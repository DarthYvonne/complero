<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Aktivitetslog</strong>
@endsection
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        <i class="fa-solid fa-clock-rotate-left" style="color: var(--primary-color);"></i> Aktivitetslog
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Se alle aktiviteter på platformen
                    </p>
                </div>

                <!-- Filter Controls -->
                <div class="card" style="min-width: 400px;">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.activity.index') }}">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label class="form-label" style="font-size: 13px; font-weight: 500;">Type</label>
                                    <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Alle typer</option>
                                        @foreach($activityTypes as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" style="font-size: 13px; font-weight: 500;">Bruger</label>
                                    <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">Alle brugere</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label" style="font-size: 13px; font-weight: 500;">Fra dato</label>
                                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" onchange="this.form.submit()">
                                </div>
                                <div class="col-6">
                                    <label class="form-label" style="font-size: 13px; font-weight: 500;">Til dato</label>
                                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" onchange="this.form.submit()">
                                </div>
                                @if(request()->hasAny(['type', 'user_id', 'date_from', 'date_to']))
                                    <div class="col-12">
                                        <a href="{{ route('admin.activity.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                            <i class="fa-solid fa-times me-1"></i> Ryd filtre
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="card">
            <div class="card-body">
                @forelse($activities as $activity)
                    <div class="activity-item" style="border-bottom: 1px solid #f0f0f0; padding: 15px 0;">
                        <div class="d-flex gap-3">
                            <!-- Icon -->
                            <div style="flex-shrink: 0;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $activity->type == 'course_created' ? '#10b981' : ($activity->type == 'course_deleted' ? '#ef4444' : 'var(--primary-color)') }}; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid {{ $activity->type == 'course_created' ? 'fa-plus' : ($activity->type == 'course_deleted' ? 'fa-trash' : ($activity->type == 'course_updated' ? 'fa-pen' : 'fa-clock')) }}" style="color: white; font-size: 16px;"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div style="flex-grow: 1;">
                                <div style="font-size: 14px; font-weight: 400; color: #333; margin-bottom: 4px;">
                                    {{ $activity->description }}
                                </div>
                                <div style="font-size: 13px; font-weight: 300; color: #999;">
                                    <i class="fa-solid fa-clock me-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                    <span class="ms-2">•</span>
                                    <span class="ms-2">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
                                    @if($activity->causer)
                                        <span class="ms-2">•</span>
                                        <span class="ms-2">
                                            <i class="fa-solid fa-user me-1"></i>
                                            {{ $activity->causer->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Type Badge -->
                            <div style="flex-shrink: 0;">
                                <span class="badge" style="background: #f3f4f6; color: #666; font-weight: 500;">
                                    {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fa-solid fa-clock-rotate-left" style="font-size: 4rem; color: #d1d5db;"></i>
                        <h5 class="mt-3 mb-2">Ingen aktivitet endnu</h5>
                        <p class="text-muted mb-0" style="font-weight: 300;">Aktiviteter vil blive vist her</p>
                    </div>
                @endforelse

                <!-- Pagination -->
                @if($activities->hasPages())
                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .activity-item:last-child {
            border-bottom: none !important;
        }
        .activity-item:first-child {
            padding-top: 0;
        }
        .activity-item:last-child {
            padding-bottom: 0;
        }
    </style>
</x-app-layout>
