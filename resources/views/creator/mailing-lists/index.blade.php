<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Mailing lister</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        <i class="fa-solid fa-envelope" style="color: #be185d;"></i> Mailing lister
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Administrer mailing lister og medlemmer
                    </p>
                </div>
                <a href="{{ route('creator.mailing-lists.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-plus me-1"></i> Opret liste
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
            <div class="card-body">
                @if($mailingLists->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="font-weight: 600; color: #333;">Navn</th>
                                    <th style="font-weight: 600; color: #333;">Beskrivelse</th>
                                    <th style="font-weight: 600; color: #333;">Medlemmer</th>
                                    <th style="font-weight: 600; color: #333;">Kurser</th>
                                    <th style="font-weight: 600; color: #333;">Ressourcer</th>
                                    <th style="font-weight: 600; color: #333;">Status</th>
                                    <th style="font-weight: 600; color: #333;">Handlinger</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mailingLists as $list)
                                    <tr>
                                        <td>
                                            <strong style="font-weight: 500;">{{ $list->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $list->slug }}</small>
                                        </td>
                                        <td style="font-weight: 300; color: #666;">
                                            {{ Str::limit($list->description, 50) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $list->members_count }} medlemmer</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $list->courses_count }} kurser</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $list->resources_count }} ressourcer</span>
                                        </td>
                                        <td>
                                            @if($list->is_active)
                                                <span class="badge bg-success">Aktiv</span>
                                            @else
                                                <span class="badge bg-secondary">Inaktiv</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('creator.mailing-lists.show', $list) }}" class="btn btn-outline-primary">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{ route('creator.mailing-lists.edit', $list) }}" class="btn btn-outline-primary">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <form action="{{ route('creator.mailing-lists.destroy', $list) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Er du sikker på, at du vil slette denne liste?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa-solid fa-envelope" style="font-size: 4rem; color: #d1d5db;"></i>
                        <h5 class="mt-3 mb-2">Ingen mailing lister endnu</h5>
                        <p class="text-muted mb-3" style="font-weight: 300;">Kom i gang med at oprette din første mailing liste</p>
                        <a href="{{ route('creator.mailing-lists.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Opret liste
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
            background: #be185d;
            border-color: #be185d;
            font-size: 14px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: #9f1239;
            border-color: #9f1239;
        }
        .table {
            font-size: 14px;
        }
    </style>
</x-app-layout>
