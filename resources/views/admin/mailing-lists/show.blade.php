<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $mailingList->name }}</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        {{ $mailingList->name }}
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        {{ $mailingList->description }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.mailing-lists.edit', $mailingList) }}" class="btn btn-primary">
                        <i class="fa-solid fa-pen me-1"></i> Rediger
                    </a>
                    <a href="{{ route('admin.mailing-lists.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Members -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-users" style="color: var(--primary-color);"></i> Medlemmer @if($mailingList->activeMembers->count() > 0)(<b>{{ $mailingList->activeMembers->count() }}</b>)@endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($mailingList->activeMembers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th style="font-weight: 600; color: #333;">Navn</th>
                                            <th style="font-weight: 600; color: #333;">Email</th>
                                            <th style="font-weight: 600; color: #333;">Tilmeldt</th>
                                            <th style="font-weight: 600; color: #333;">Handlinger</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mailingList->activeMembers as $member)
                                            <tr>
                                                <td style="font-weight: 500;">{{ $member->name }}</td>
                                                <td style="font-weight: 300; color: #666;">{{ $member->email }}</td>
                                                <td style="font-weight: 300; color: #666;">
                                                    {{ $member->pivot->subscribed_at ? $member->pivot->subscribed_at->format('d/m/Y') : '-' }}
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.mailing-lists.members.remove', [$mailingList, $member]) }}" method="POST" class="d-inline"
                                                          onsubmit="return confirm('Er du sikker på, at du vil fjerne dette medlem?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fa-solid fa-trash"></i> Fjern
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-users" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen medlemmer endnu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Courses -->
                <div class="card mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> Forløb @if($mailingList->courses->count() > 0)(<b>{{ $mailingList->courses->count() }}</b>)@endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($mailingList->courses->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->courses as $course)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $course->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($course->description, 80) }}</small>
                                        </div>
                                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye"></i> Se
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen forløb tildelt denne liste endnu</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Resources -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                            <i class="fa-solid fa-photo-film" style="color: var(--primary-color);"></i> Downloads @if($mailingList->resources->count() > 0)(<b>{{ $mailingList->resources->count() }}</b>)@endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($mailingList->resources->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->resources as $resource)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $resource->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($resource->description, 80) }}</small>
                                        </div>
                                        <a href="{{ route('admin.resources.show', $resource) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-eye"></i> Se
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen downloads tildelt denne liste endnu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- List Details -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">Liste detaljer</h5>

                        <!-- Status -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Status</strong>
                            @if($mailingList->is_active)
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-circle-check"></i> Aktiv
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fa-solid fa-circle-pause"></i> Inaktiv
                                </span>
                            @endif
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Slug</strong>
                            <code class="small">{{ $mailingList->slug }}</code>
                        </div>

                        <!-- Created/Updated -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Oprettet</strong>
                            {{ $mailingList->created_at->format('d/m/Y H:i') }}
                        </div>

                        <div>
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Sidst opdateret</strong>
                            {{ $mailingList->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">Handlinger</h5>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.mailing-lists.edit', $mailingList) }}" class="btn btn-primary">
                                <i class="fa-solid fa-pen"></i> Rediger liste
                            </a>

                            <hr class="my-2">

                            <form action="{{ route('admin.mailing-lists.destroy', $mailingList) }}" method="POST"
                                  onsubmit="return confirm('Er du sikker på, at du vil slette denne liste? Kurser og ressourcer vil blive gjort tilgængelige for alle.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fa-solid fa-trash"></i> Slet liste
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
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
            font-size: 14px;
        }
    </style>
</x-app-layout>
