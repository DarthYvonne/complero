<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Grupper</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        <i class="fa-solid fa-user-group" style="color: var(--primary-color);"></i> Grupper
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer grupper og medlemmer
                    </p>
                </div>
                <a href="{{ route('creator.mailing-lists.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-plus me-1"></i> Opret gruppe
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

        <!-- Mailing Lists Table -->
        <div class="card">
            <div class="card-body" style="padding: 0;">
                @if($mailingLists->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="font-weight: 600; color: #333; text-align: left;">Navn</th>
                                    <th style="font-weight: 600; color: #333; text-align: left;">Medlemmer</th>
                                    <th style="font-weight: 600; color: #333; text-align: left;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mailingLists as $list)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('creator.mailing-lists.show', $list) }}'">
                                        <td style="font-weight: 500; color: #333; text-align: left;">
                                            <i class="fa-solid fa-user-group me-2" style="color: var(--primary-color);"></i> {{ $list->name }}
                                        </td>
                                        <td style="font-weight: 300; color: #666; text-align: left;">
                                            {{ $list->members_count }}
                                        </td>
                                        <td style="font-weight: 300; color: #666; text-align: left;">
                                            @if($list->is_active)
                                                Aktiv
                                            @else
                                                Inaktiv
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa-solid fa-user-group" style="font-size: 4rem; color: #d1d5db;"></i>
                        <h5 class="mt-3 mb-2">Ingen grupper endnu</h5>
                        <p class="text-muted mb-3" style="font-weight: 300;">Kom i gang med at oprette din første gruppe</p>
                        <a href="{{ route('creator.mailing-lists.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Opret gruppe
                        </a>
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
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            font-size: 14px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .table {
            font-size: 16px;
        }
        .table th,
        .table td {
            padding: 12px 16px;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</x-app-layout>
