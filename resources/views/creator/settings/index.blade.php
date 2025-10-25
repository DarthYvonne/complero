<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Indstillinger</strong>
@endsection

    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                    <i class="fa-solid fa-gear" style="color: var(--primary-color);"></i> Indstillinger
                </h1>
                <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                    Administrer dine kontoindstillinger
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <!-- Profile Settings Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0" style="font-weight: 600;">Profiloplysninger</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('creator.settings.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Navn <span class="text-danger">*</span></label>
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
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
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

                            <!-- Organization Name -->
                            <div class="mb-3">
                                <label for="organization_name" class="form-label">Organisationsnavn</label>
                                <input type="text"
                                       class="form-control @error('organization_name') is-invalid @enderror"
                                       id="organization_name"
                                       name="organization_name"
                                       value="{{ old('organization_name', $user->organization_name) }}"
                                       placeholder="Valgfrit">
                                @error('organization_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Dette navn vises på dine kurser og ressourcer</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-1"></i> Gem ændringer
                                </button>
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
            border-radius: 6px;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
    </style>
</x-app-layout>
