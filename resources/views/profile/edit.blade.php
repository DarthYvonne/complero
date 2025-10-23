<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Min profil</strong>
@endsection
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                <i class="fa-solid fa-user" style="color: var(--primary-color);"></i> Min profil
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Administrer dine personlige oplysninger
            </p>
        </div>

        <!-- Success Message -->
        @if(session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                Profil opdateret succesfuldt
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                Adgangskode opdateret succesfuldt
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Information -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 20px;">
                            Profiloplysninger
                        </h2>

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3">
                                <div class="col-md-6">
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

                                <div class="col-md-6">
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

                                @if($user->role === 'admin' || $user->role === 'creator')
                                    <div class="col-12">
                                        <hr class="my-3">
                                        <h3 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 15px;">
                                            Organisationsoplysninger
                                        </h3>
                                        <p style="font-size: 13px; font-weight: 300; color: #999; margin-bottom: 15px;">
                                            Disse oplysninger bruges i breadcrumb-linjen
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="organization_name" class="form-label">Organisationsnavn</label>
                                        <input type="text"
                                               class="form-control @error('organization_name') is-invalid @enderror"
                                               id="organization_name"
                                               name="organization_name"
                                               value="{{ old('organization_name', $user->organization_name) }}">
                                        @error('organization_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="organization_email" class="form-label">Organisations e-mail</label>
                                        <input type="email"
                                               class="form-control @error('organization_email') is-invalid @enderror"
                                               id="organization_email"
                                               name="organization_email"
                                               value="{{ old('organization_email', $user->organization_email) }}">
                                        @error('organization_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="website" class="form-label">Hjemmeside</label>
                                        <input type="url"
                                               class="form-control @error('website') is-invalid @enderror"
                                               id="website"
                                               name="website"
                                               value="{{ old('website', $user->website) }}"
                                               placeholder="https://example.com">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control @error('bio') is-invalid @enderror"
                                                  id="bio"
                                                  name="bio"
                                                  rows="4">{{ old('bio', $user->bio) }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 20px;">
                            Skift adgangskode
                        </h2>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="current_password" class="form-label">Nuværende adgangskode</label>
                                    <input type="password"
                                           class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password"
                                           name="current_password"
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="password" class="form-label">Ny adgangskode</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="password_confirmation" class="form-label">Bekræft adgangskode</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-key me-1"></i> Opdater adgangskode
                                    </button>
                                </div>
                            </div>
                        </form>
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
        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }
    </style>
</x-app-layout>
