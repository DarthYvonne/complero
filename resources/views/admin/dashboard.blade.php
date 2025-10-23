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
                <i class="fa-solid fa-gauge" style="color: var(--primary-color);"></i> {{ $organizationName }}
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Administrer din platform
            </p>
        </div>

        <!-- Stats Cards - 4 EQUAL COLUMNS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Medlemmer</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_members'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Skabere</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_creators'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Administratorer</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_admins'] }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-1 small" style="font-weight: 300;">Total Brugere</p>
                        <h2 class="mb-0" style="font-size: 28px; font-weight: 700;">{{ $stats['total_users'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions - FULL WIDTH -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 15px;">Hurtige handlinger</h2>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Opret kursus
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-book me-1"></i> Se alle kurser
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-users me-1"></i> Administrer brugere
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users - FULL WIDTH -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 15px;">Seneste brugere</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="border-bottom: 2px solid #e0e0e0;">
                                <tr>
                                    <th style="font-weight: 600; font-size: 13px; color: #666; padding: 12px;">Navn</th>
                                    <th style="font-weight: 600; font-size: 13px; color: #666; padding: 12px;">E-mail</th>
                                    <th style="font-weight: 600; font-size: 13px; color: #666; padding: 12px;">Rolle</th>
                                    <th style="font-weight: 600; font-size: 13px; color: #666; padding: 12px;">Tilmeldt</th>
                                    <th style="font-weight: 600; font-size: 13px; color: #666; padding: 12px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                <tr>
                                    <td style="padding: 12px; font-size: 14px;">{{ $user->name }}</td>
                                    <td style="padding: 12px; font-size: 14px; color: #666;">{{ $user->email }}</td>
                                    <td style="padding: 12px;">
                                        @if($user->role === 'admin')
                                        <span class="badge bg-warning">Admin</span>
                                        @elseif($user->role === 'creator')
                                        <span class="badge bg-success">Skaber</span>
                                        @else
                                        <span class="badge" style="background-color: var(--primary-color);">Medlem</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; font-size: 13px; color: #999; font-weight: 300;">{{ $user->created_at->diffForHumans() }}</td>
                                    <td style="padding: 12px;">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                            Rediger
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color: #999; font-weight: 300;">
                                        Ingen brugere endnu
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
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