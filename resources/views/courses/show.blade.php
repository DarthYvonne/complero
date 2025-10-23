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
                        <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> {{ $course->title }}
                    </h1>
                    <p style="font-size: 16px; font-weight: 300; color: #999; margin: 0;">
                        Af: {{ $course->creator->name }}
                        @if($course->lessons->count() > 0)
                            <span style="margin: 0 8px;">•</span>
                            {{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'lektion' : 'lektioner' }}
                        @endif
                        <span style="margin: 0 8px;">•</span>
                        Pris:
                        @if($course->is_free)
                            <span style="color: #10b981; font-weight: 500;">Gratis for dig</span>
                        @else
                            <span style="font-weight: 500;">€{{ number_format($course->price, 2) }}</span>
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
                <div class="card mb-4">
                    <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}" style="max-height: 400px; object-fit: cover;">
                </div>

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
                            <div style="font-size: 14px; font-weight: 300; color: #666;">{!! $course->description !!}</div>
                        </div>
                    @endif
                </div>

                <!-- Enroll Button -->
                <button class="btn btn-primary btn-lg w-100">
                    <i class="fa-solid fa-rocket me-2"></i> Tag forløbet nu
                </button>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Lessons -->
                @if($course->lessons->count() > 0)
                    <div class="card">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0" style="font-size: 16px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-list"></i> Lektioner
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($course->lessons->sortBy('order') as $index => $lesson)
                                    <a href="{{ route('lessons.show', [$course, $lesson]) }}"
                                       class="list-group-item list-group-item-action"
                                       style="border-left: none; border-right: none;">
                                        <div class="d-flex align-items-start">
                                            <div class="me-2" style="min-width: 24px;">
                                                <span style="font-size: 13px; font-weight: 300; color: #999;">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div style="font-size: 14px; font-weight: 400; color: #333;">
                                                    {{ $lesson->title }}
                                                </div>
                                                @if($lesson->duration_minutes)
                                                    <small style="font-size: 12px; font-weight: 300; color: #999;">
                                                        {{ $lesson->duration_minutes }} min
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
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
