<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Grupper</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $mailingList->name }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Indhold</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                Gruppe: <span style="font-weight: 100;">{{ $mailingList->name }}</span>
            </h1>
        </div>

        <!-- Horizontal Tab Menu -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.show', $mailingList) }}">
                    <i class="fa-solid fa-circle-user me-1"></i> Medlemmer
                </a>
            </li>
            <li class="nav-item dropdown" role="presentation">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fa-solid fa-plus me-1"></i> Sign up
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.signup-forms', $mailingList) }}">
                        <i class="fa-solid fa-code me-2"></i> Forms
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.qr-code', $mailingList) }}">
                        <i class="fa-solid fa-qrcode me-2"></i> QR Code
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('creator.mailing-lists.landing-page', $mailingList) }}">
                        <i class="fa-solid fa-image me-2"></i> Landing page
                    </a></li>
                </ul>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.welcome', $mailingList) }}">
                    <i class="fa-solid fa-heart me-1"></i> Velkomst
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" href="{{ route('creator.mailing-lists.content', $mailingList) }}">
                    <i class="fa-solid fa-circle-play me-1"></i> Indhold
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="{{ route('creator.mailing-lists.settings', $mailingList) }}">
                    <i class="fa-solid fa-gear me-1"></i> Indstillinger
                </a>
            </li>
        </ul>

        <!-- Page Description -->
        <div class="mb-4">
            <h2 style="font-size: 20px; font-weight: 600; color: #333;">
                <i class="fa-solid fa-circle-play me-2" style="color: var(--primary-color);"></i>
                Indhold til medlemmer
            </h2>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Courses -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; color: #333;">
                            <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> Forløb til medlemmer @if($mailingList->courses->count() > 0)(<b>{{ $mailingList->courses->count() }}</b>)@endif
                        </h5>
                        @if($availableCourses->count() > 0)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignCoursesModal">
                                <i class="fa-solid fa-plus me-1"></i> Tilbyd forløb
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($mailingList->courses->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->courses as $course)
                                    <a href="{{ route('creator.courses.show', $course) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="text-decoration: none; color: inherit;">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $course->title }}</strong>
                                            <br>
                                            <small class="text-muted">{!! Str::limit(strip_tags($course->description), 80) !!}</small>
                                        </div>
                                        <i class="fa-solid fa-chevron-right" style="color: #999;"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen forløb tildelt denne gruppe endnu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Resources -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0" style="font-size: 18px; color: #333;">
                            <i class="fa-solid fa-photo-film" style="color: var(--primary-color);"></i> Materialer til medlemmer @if($mailingList->resources->count() > 0)(<b>{{ $mailingList->resources->count() }}</b>)@endif
                        </h5>
                        @if($availableResources->count() > 0)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignResourcesModal">
                                <i class="fa-solid fa-plus me-1"></i> Tilbyd materiale
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($mailingList->resources->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($mailingList->resources as $resource)
                                    <a href="{{ route('creator.resources.show', $resource) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="text-decoration: none; color: inherit;">
                                        <div>
                                            <strong style="font-weight: 500;">{{ $resource->title }}</strong>
                                            <br>
                                            <small class="text-muted">{!! Str::limit(strip_tags($resource->description), 80) !!}</small>
                                        </div>
                                        <i class="fa-solid fa-chevron-right" style="color: #999;"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen materialer tildelt denne gruppe endnu</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Courses Modal -->
    <div class="modal fade" id="assignCoursesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-circle-play me-2" style="color: var(--primary-color);"></i>
                        Tilbyd forløb til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-courses', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke forløb der skal være tilgængelige for medlemmer af denne liste.</p>

                        @if($availableCourses->count() > 0)
                            <div class="list-group">
                                @foreach($availableCourses as $course)
                                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor: pointer;">
                                        <input class="form-check-input mt-1" type="checkbox" name="course_ids[]" value="{{ $course->id }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block" style="font-weight: 600;">{{ $course->title }}</strong>
                                            <small class="text-muted">{{ Str::limit($course->description, 100) }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige forløb</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i> Tilbyd dette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Resources Modal -->
    <div class="modal fade" id="assignResourcesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-photo-film me-2" style="color: var(--primary-color);"></i>
                        Tilbyd materialer til {{ $mailingList->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('creator.mailing-lists.assign-resources', $mailingList) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Vælg hvilke materialer der skal være tilgængelige for medlemmer af denne liste.</p>

                        @if($availableResources->count() > 0)
                            <div class="list-group">
                                @foreach($availableResources as $resource)
                                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor: pointer;">
                                        <input class="form-check-input mt-1" type="checkbox" name="resource_ids[]" value="{{ $resource->id }}">
                                        <div class="flex-grow-1">
                                            <strong class="d-block" style="font-weight: 600;">{{ $resource->title }}</strong>
                                            <small class="text-muted">{{ Str::limit($resource->description, 100) }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-photo-film" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="mt-3 mb-0" style="font-weight: 300; color: #666;">Ingen tilgængelige materialer</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuller</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check me-1"></i> Tilbyd dette
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: none;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

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

        /* Dropdown on hover */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin-top: 0;
        }
    </style>
</x-app-layout>
