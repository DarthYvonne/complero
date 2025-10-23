<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Mailing lister</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Rediger</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        <i class="fa-solid fa-pen" style="color: var(--primary-color);"></i> Rediger mailing liste
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        {{ $mailingList->name }}
                    </p>
                </div>
                <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til liste
                </a>
            </div>
        </div>

        <!-- Mailing List Form -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('creator.mailing-lists.update', $mailingList) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Navn <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $mailingList->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Beskrivelse</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          name="description"
                                          rows="4">{{ old('description', $mailingList->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $mailingList->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Listen er aktiv
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-circle-check me-1"></i> Gem ændringer
                                </button>
                                <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" class="btn btn-outline-secondary">
                                    Annuller
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info" style="color: var(--primary-color);"></i> Listeinfo
                        </h5>
                        <div style="font-size: 14px; font-weight: 300; color: #666;">
                            <div class="mb-3">
                                <strong class="d-block mb-1">Oprettet</strong>
                                {{ $mailingList->created_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Sidst opdateret</strong>
                                {{ $mailingList->updated_at->format('d/m/Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Slug</strong>
                                <code>{{ $mailingList->slug }}</code>
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Medlemmer</strong>
                                {{ $mailingList->members()->wherePivot('status', 'active')->count() }}
                            </div>

                            <div class="mb-3">
                                <strong class="d-block mb-1">Kurser</strong>
                                {{ $mailingList->courses()->count() }}
                            </div>

                            <div>
                                <strong class="d-block mb-1">Ressourcer</strong>
                                {{ $mailingList->resources()->count() }}
                            </div>
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
        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #333;
        }
    </style>
</x-app-layout>
