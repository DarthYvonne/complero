<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $course->title }}</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        Forløb: <span style="font-weight: 100;">{{ $course->title }}</span>
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        <i class="fa-solid fa-user"></i> {{ $course->creator->name }}
                        <span style="margin: 0 8px;">•</span>
                        <i class="fa-solid fa-calendar"></i> {{ $course->created_at->format('d/m/Y') }}
                        @if($course->lessons->count() > 0)
                            <span style="margin: 0 8px;">•</span>
                            <i class="fa-solid fa-circle-play"></i> {{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'lektion' : 'lektioner' }}
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløb
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Course Image -->
                @if($course->image_url)
                    <div class="card mb-4">
                        <img src="{{ Storage::url($course->image_url) }}" class="card-img-top" alt="{{ $course->title }}" style="max-height: 400px; object-fit: cover;">
                    </div>
                @endif

                <!-- Description / Tabs -->
                <div class="card mb-4">
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
                            <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                                <i class="fa-solid fa-align-left" style="color: #2563eb;"></i> Om dette forløb
                            </h5>
                            <div style="font-size: 14px; font-weight: 300; color: #666;">{!! $course->description !!}</div>
                        </div>
                    @endif
                </div>

                <!-- Lessons Section -->
                @if($course->lessons->count() > 0)
                    <div class="card">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-circle-play" style="color: #2563eb;"></i> Lektioner
                                <span class="badge bg-secondary ms-2">{{ $course->lessons->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($course->lessons->sortBy('order') as $lesson)
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="list-group-item list-group-item-action lesson-item" style="text-decoration: none; transition: background-color 0.2s;">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fa-solid fa-circle-play me-2" style="color: #2563eb;"></i>
                                                    <strong style="font-size: 16px; font-weight: 600; color: #333;">{{ $lesson->title }}</strong>
                                                    @if($lesson->duration_minutes)
                                                        <span class="badge bg-light text-dark ms-2" style="font-weight: 300;">{{ $lesson->duration_minutes }} min</span>
                                                    @endif
                                                </div>
                                                @if($lesson->description)
                                                    <p class="mb-0 ms-4" style="font-size: 14px; font-weight: 300; color: #666;">{{ $lesson->description }}</p>
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                <i class="fa-solid fa-chevron-right" style="color: #999;"></i>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Info -->
                <div class="card">
                    <div class="card-body">
                        <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                            <i class="fa-solid fa-circle-info" style="color: #2563eb;"></i> Forløb information
                        </h5>

                        <!-- Price -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Pris</strong>
                            @if($course->is_free)
                                <span style="color: #10b981; font-weight: 600; font-size: 20px;">
                                    <i class="fa-solid fa-gift"></i> Gratis
                                </span>
                            @else
                                <span style="font-weight: 600; font-size: 20px;">€{{ number_format($course->price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Lessons Count -->
                        <div class="mb-3">
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Antal lektioner</strong>
                            <span style="font-size: 14px; font-weight: 300; color: #666;">{{ $course->lessons->count() }}</span>
                        </div>

                        <!-- Total Duration -->
                        @if($course->lessons->sum('duration_minutes') > 0)
                            <div class="mb-3">
                                <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Samlet varighed</strong>
                                <span style="font-size: 14px; font-weight: 300; color: #666;">{{ $course->lessons->sum('duration_minutes') }} minutter</span>
                            </div>
                        @endif

                        <!-- Enrollments -->
                        <div>
                            <strong class="d-block small mb-1" style="color: #999; font-weight: 500;">Deltagere</strong>
                            <span style="font-size: 14px; font-weight: 300; color: #666;">{{ $course->enrollments->count() }}</span>
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
            background: #be185d;
            border-color: #be185d;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
        .btn-primary:hover {
            background: #9f1239;
            border-color: #9f1239;
        }
        .btn-outline-secondary {
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
            border-color: #f0f0f0;
            padding: 1rem 0;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .lesson-item:hover {
            background-color: #f9fafb;
        }
        .card-header-tabs {
            margin-left: 0;
            margin-right: 0;
        }
        .card-header-tabs .nav-item:first-child .nav-link {
            padding-left: 1rem;
        }
    </style>
</x-app-layout>
