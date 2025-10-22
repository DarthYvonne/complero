<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                <i class="fa-solid fa-gear" style="color: #be185d;"></i> Indstillinger
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Administrer organisationsindstillinger
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Settings Form -->
        <div class="card">
            <div class="card-body">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin-bottom: 20px;">Organisation</h2>

                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="organization_name" class="form-label">Organisationsnavn</label>
                        <input type="text"
                               class="form-control @error('organization_name') is-invalid @enderror"
                               id="organization_name"
                               name="organization_name"
                               value="{{ old('organization_name', $settings['organization_name']) }}"
                               required>
                        @error('organization_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" style="font-weight: 300;">Dette navn vises i breadcrumb-linjen</div>
                    </div>

                    <div class="mb-3">
                        <label for="organization_email" class="form-label">E-mail</label>
                        <input type="email"
                               class="form-control @error('organization_email') is-invalid @enderror"
                               id="organization_email"
                               name="organization_email"
                               value="{{ old('organization_email', $settings['organization_email']) }}">
                        @error('organization_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="organization_website" class="form-label">Hjemmeside</label>
                        <input type="url"
                               class="form-control @error('organization_website') is-invalid @enderror"
                               id="organization_website"
                               name="organization_website"
                               value="{{ old('organization_website', $settings['organization_website']) }}"
                               placeholder="https://example.com">
                        @error('organization_website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-circle-check me-1"></i> Gem indstillinger
                    </button>
                </form>
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
        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }
    </style>
</x-app-layout>
