<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('admin.courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $course->title }}</strong>
@endsection
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                            Forløb: <span style="font-weight: 100;">{{ $course->title }}</span>
                        </h1>
                        <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                            <i class="fa-solid fa-user"></i> Oprettet af {{ $course->creator->name }}
                            <span class="mx-2">•</span>
                            <i class="fa-solid fa-calendar"></i> {{ $course->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fa-solid fa-eye me-1"></i> Forhåndsvisning
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                            <i class="fa-solid fa-pen me-1"></i> Rediger
                        </a>
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Tilbage
                        </a>
                    </div>
                </div>
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
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Image -->
                @if($course->image_url)
                    <div class="card border-0 shadow-sm mb-4">
                        <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}" style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Description / Tabs -->
                <div class="card border-0 shadow-sm mb-4">
                    @if($course->tabs->count() > 0)
                        <!-- Tabs Navigation -->
                        <div class="card-header bg-white border-bottom" style="padding: 0;">
                            <ul class="nav nav-tabs card-header-tabs" id="courseTabs" role="tablist" style="border-bottom: none; margin-bottom: 0;">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="intro-tab" data-bs-toggle="tab" data-bs-target="#intro" type="button" role="tab" aria-controls="intro" aria-selected="true">
                                        Intro
                                    </button>
                                </li>
                                @foreach($course->tabs as $index => $tab)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-{{ $tab->id }}-tab" data-bs-toggle="tab" data-bs-target="#tab-{{ $tab->id }}" type="button" role="tab" aria-controls="tab-{{ $tab->id }}" aria-selected="false">
                                            {{ $tab->title }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Tabs Content -->
                        <div class="card-body">
                            <div class="tab-content" id="courseTabsContent">
                                <div class="tab-pane fade show active" id="intro" role="tabpanel" aria-labelledby="intro-tab">
                                    {!! $course->description !!}
                                </div>
                                @foreach($course->tabs as $tab)
                                    <div class="tab-pane fade" id="tab-{{ $tab->id }}" role="tabpanel" aria-labelledby="tab-{{ $tab->id }}-tab">
                                        {!! $tab->content !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- No tabs, just show description -->
                        <div class="card-body">
                            <h5 class="card-title">Beskrivelse</h5>
                            <div class="card-text">{!! $course->description !!}</div>
                        </div>
                    @endif
                </div>

                <!-- Lessons Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-circle-play"></i> Lektioner
                            <span class="badge bg-secondary ms-2">{{ $course->lessons->count() }}</span>
                        </h5>
                        <a href="{{ route('admin.courses.lessons.create', $course) }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Tilføj lektion
                        </a>
                    </div>
                    <div class="card-body">
                        @if($course->lessons->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($course->lessons->sortBy('order') as $lesson)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('lessons.show', [$course, $lesson]) }}" style="text-decoration: none; color: inherit;">
                                                <i class="fa-solid fa-circle-play text-primary me-2"></i>
                                                <strong>{{ $lesson->title }}</strong>
                                                @if($lesson->duration_minutes)
                                                    <span class="text-muted ms-2">({{ $lesson->duration_minutes }} min)</span>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="btn btn-outline-secondary" target="_blank" title="Se lektion">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-outline-primary" title="Rediger">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Er du sikker på, at du vil slette denne lektion?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Slet">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem;"></i>
                                <p class="mt-3">Ingen lektioner endnu</p>
                                <p class="small">Tilføj lektioner for at bygge dit forløb</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Details -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Kursus detaljer</h5>

                        <!-- Status -->
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">Status</strong>
                            @if($course->is_published)
                                <span class="badge bg-success">
                                    <i class="fa-solid fa-circle-check"></i> Publiceret
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fa-solid fa-file"></i> Kladde
                                </span>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">Pris</strong>
                            @if($course->is_free)
                                <span class="text-success fw-bold">
                                    <i class="fa-solid fa-gift"></i> Gratis
                                </span>
                            @else
                                <span class="fw-bold">€{{ number_format($course->price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">URL-slug</strong>
                            <code class="small">{{ $course->slug }}</code>
                        </div>

                        <!-- Lessons Count -->
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">Antal lektioner</strong>
                            {{ $course->lessons->count() }}
                        </div>

                        <!-- Enrollments -->
                        <div class="mb-3">
                            <strong class="d-block text-muted small mb-1">Tilmeldinger</strong>
                            {{ $course->enrollments->count() }}
                        </div>

                        <!-- Stripe Price ID -->
                        @if($course->stripe_price_id)
                            <div class="mb-3">
                                <strong class="d-block text-muted small mb-1">Stripe Price ID</strong>
                                <code class="small">{{ $course->stripe_price_id }}</code>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Handlinger</h5>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-primary">
                                <i class="fa-solid fa-pen"></i> Rediger forløb
                            </a>

                            @if($course->is_published)
                                <button class="btn btn-outline-secondary" disabled>
                                    <i class="fa-solid fa-eye-slash"></i> Gem som kladde
                                </button>
                            @else
                                <button class="btn btn-outline-success" disabled>
                                    <i class="fa-solid fa-circle-check"></i> Publicer forløb
                                </button>
                            @endif

                            <hr>

                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                                  onsubmit="return confirm('Er du sikker på, at du vil slette dette forløb? Alle lektioner vil også blive slettet.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fa-solid fa-trash"></i> Slet forløb
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-header-tabs {
            margin-left: 0;
            margin-right: 0;
        }
        .card-header-tabs .nav-item:first-child .nav-link {
            padding-left: 1rem;
        }
    </style>
</x-app-layout>
