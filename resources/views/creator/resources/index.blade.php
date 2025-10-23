<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Materialer</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Materialer
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer alle materialer på platformen
                    </p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @if(auth()->user()->role === 'admin' && request()->routeIs('admin.resources.index') && $creators->count() > 0)
                        <!-- Creator Filter -->
                        <form method="GET" action="{{ route('admin.resources.index') }}">
                            <select name="creator_id" class="form-select" style="width: 250px;" onchange="this.form.submit()">
                                <option value="">Alle creators</option>
                                @foreach($creators as $creator)
                                    <option value="{{ $creator->id }}" {{ request('creator_id') == $creator->id ? 'selected' : '' }}>
                                        {{ $creator->organization_name ?: $creator->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        @if(request('creator_id'))
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-times"></i>
                            </a>
                        @endif
                    @endif
                    <a href="{{ request()->routeIs('admin.resources.index') ? route('admin.resources.create') : route('creator.resources.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-circle-plus me-1"></i> Opret materiale
                    </a>
                </div>
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
                <x-resource-card :resource="$resource" />
            @empty
                <div class="col-12">
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <i class="fa-solid fa-glasses" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Intet materiale endnu</h5>
                            <p class="text-muted mb-3" style="font-weight: 300;">Kom i gang med at oprette dit første materiale</p>
                            <a href="{{ route('creator.resources.create') }}" class="btn btn-primary">
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
