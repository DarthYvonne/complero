<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Brugere</strong>
@endsection
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                <i class="fa-solid fa-users" style="color: #be185d;"></i> Brugere
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Administrer alle brugere på platformen
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Users Table -->
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
                                <th style="font-weight: 600; font-size: 13px; color: #666; padding: 12px;" class="text-end">Handlinger</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td style="padding: 12px;">
                                        <div style="font-size: 14px; font-weight: 500;">{{ $user->name }}</div>
                                        @if($user->imported_from)
                                            <small style="font-size: 12px; font-weight: 300; color: #999;">
                                                <i class="fa-solid fa-download"></i> Importeret fra {{ $user->imported_from }}
                                            </small>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; font-size: 14px; color: #666;">{{ $user->email }}</td>
                                    <td style="padding: 12px;">
                                        @if($user->role === 'admin')
                                            <span class="badge bg-warning">Admin</span>
                                        @elseif($user->role === 'creator')
                                            <span class="badge bg-success">Skaber</span>
                                        @else
                                            <span class="badge" style="background-color: #be185d;">Medlem</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; font-size: 13px; color: #999; font-weight: 300;">{{ $user->created_at->diffForHumans() }}</td>
                                    <td style="padding: 12px;" class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-pen"></i> Rediger
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Er du sikker på, at du vil slette denne bruger?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa-solid fa-trash"></i> Slet
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="fa-solid fa-users" style="font-size: 3rem;"></i>
                                        <p class="mt-2 mb-0">Ingen brugere fundet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .btn {
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
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
        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }
        .table tbody tr:last-child {
            border-bottom: none;
        }
    </style>
</x-app-layout>
