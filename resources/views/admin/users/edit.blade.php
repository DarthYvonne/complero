<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.users.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Brugere</a>
    <span style="margin: 0 8px;">/</span>
    <span style="color: #999;">{{ $user->name }}</span>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Rediger</strong>
@endsection
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-1">Rediger bruger</h1>
                <p class="text-muted mb-0">Opdater brugerinformation og rolle</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Navn</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Rolle</label>
                                <select class="form-select @error('role') is-invalid @enderror"
                                        id="role"
                                        name="role"
                                        required>
                                    <option value="member" {{ old('role', $user->role) === 'member' ? 'selected' : '' }}>
                                        Medlem
                                    </option>
                                    <option value="creator" {{ old('role', $user->role) === 'creator' ? 'selected' : '' }}>
                                        Skaber
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <strong>Medlem:</strong> Kan tilmelde sig kurser og få adgang til ressourcer<br>
                                    <strong>Skaber:</strong> Kan oprette og administrere egne kurser og ressourcer<br>
                                    <strong>Administrator:</strong> Fuld adgang til hele platformen
                                </div>
                            </div>

                            <!-- Website -->
                            <div class="mb-3">
                                <label for="website" class="form-label">Hjemmeside (valgfrit)</label>
                                <input type="url"
                                       class="form-control @error('website') is-invalid @enderror"
                                       id="website"
                                       name="website"
                                       value="{{ old('website', $user->website) }}"
                                       placeholder="https://eksempel.dk">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bio -->
                            <div class="mb-4">
                                <label for="bio" class="form-label">Bio (valgfrit)</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror"
                                          id="bio"
                                          name="bio"
                                          rows="4"
                                          placeholder="Kort beskrivelse af brugeren...">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- User Info Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Brugerinfo</h5>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Bruger ID</small>
                            <strong>#{{ $user->id }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Oprettet</small>
                            <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                            <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Sidst opdateret</small>
                            <strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong>
                            <div class="text-muted small">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>

                        @if($user->imported_from)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Import kilde</small>
                                <span class="badge bg-info">{{ $user->imported_from }}</span>
                                @if($user->external_id)
                                    <div class="text-muted small mt-1">ID: {{ $user->external_id }}</div>
                                @endif
                            </div>
                        @endif

                        @if($user->email_verified_at)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">E-mail verificeret</small>
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span class="text-success">Ja</span>
                            </div>
                        @else
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">E-mail verificeret</small>
                                <i class="fa-solid fa-circle-xmark text-danger"></i>
                                <span class="text-danger">Nej</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
        }
        .btn {
            border-radius: 6px;
        }
    </style>
</x-app-layout>
